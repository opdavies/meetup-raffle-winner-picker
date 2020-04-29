<?php

namespace App\Command;

use DateInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetRaffleWinnerCommand extends Command
{
    protected static $defaultName = 'app:get-raffle-winner';

    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;

    /**
     * @var \Symfony\Contracts\Cache\CacheInterface
     */
    private $cache;

    /**
     * All of the RSVPs for this event.
     *
     * @var \Illuminate\Support\Collection
     */
    private $rsvps;

    /**
     * All of the 'yes' RSVPs for this event.
     *
     * @var \Illuminate\Support\Collection
     */
    private $yesRsvps;

    /**
     * The picked winner.
     *
     * @var null|array
     */
    private $winner;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        string $name = null
    ) {
        parent::__construct($name);

        $this->client = $client;
        $this->cache = $cache;
        $this->rsvps = new Collection();
        $this->yesRsvps = new Collection();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument(
                'event_id',
                InputArgument::REQUIRED,
                'The meetup.com event ID'
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);

        $this->retrieveRsvps($input);
        $io->section(sprintf('%s \'yes\' RSVPs', $this->yesRsvps->count()));
        $io->listing($this->yesRsvps->pluck('member.name')->sort()->toArray());

        $this->pickWinner();
        $io->success(
            sprintf('Winner: %s', Arr::get($this->winner, 'member.name'))
        );

        $this->openWinnerPhoto();

        return 0;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function retrieveRsvps(InputInterface $input): void
    {
        $eventId = $input->getArgument('event_id');
        $rsvps = $this->cache->getItem(sprintf('rsvps.%d', $eventId));

        if (!$rsvps->isHit()) {
            $response = $this->client->request(
                'GET',
                vsprintf(
                    'https://api.meetup.com/%s/events/%d/rsvps',
                    [
                      'php-south-wales',
                      $eventId,
                    ]
                )
            );

            $rsvps->expiresAfter(DateInterval::createFromDateString('1 hour'));
            $this->rsvps = new Collection($response->toArray());
            $this->cache->save($rsvps->set($this->rsvps));
        } else {
            $this->rsvps = $rsvps->get();
        }

        $this->yesRsvps = $this->rsvps->filter(function (array $rsvp): bool {
            return $rsvp['response'] == 'yes';
        });
    }

    private function pickWinner(): void
    {
        $this->winner = $this->yesRsvps->random(1)->first();
    }

    private function openWinnerPhoto(): void
    {
        if ($photo = Arr::get($this->winner, 'member.photo.photo_link')) {
            sleep(3);
            exec(sprintf('xdg-open %s', $photo));
        }
    }

}