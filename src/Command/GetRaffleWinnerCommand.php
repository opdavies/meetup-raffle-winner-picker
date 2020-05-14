<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Client\EventClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\CacheInterface;
use Tightenco\Collect\Support\Collection;

final class GetRaffleWinnerCommand extends Command
{

    protected static $defaultName = 'app:get-raffle-winner';

    private EventClient $client;

    private CacheInterface $cache;

    private ?array $winner;

    public function __construct(
        EventClient $client,
        CacheInterface $cache,
        string $name = null
    ) {
        parent::__construct($name);

        $this->client = $client;
        $this->cache = $cache;
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
        $eventId = (int)$input->getArgument('event_id');

        // TODO: Re-add caching.
        $response = $this->client->getEventData($eventId);
        $rsvps = $response['rsvps'];

        $this->pickWinner($rsvps);

        $io->title(
            sprintf(
                '%s - %s',
                $response['eventData']['group']['name'],
                $response['eventData']['name']
            )
        );

        $io->text(rtrim($response['eventData']['link'], '/'));

        $io->section(
            sprintf(
                '%s \'yes\' RSVPs (excluding hosts)',
                $response['rsvps']->count()
            )
        );
        $io->listing(
            $response['rsvps']->pluck('member.name')->sort()->toArray()
        );
        $io->success(
            sprintf('Winner: %s', $this->winner['member']['name'])
        );

        $this->openWinnerPhoto();

        return 0;
    }

    private function pickWinner(Collection $rsvps): void
    {
        $this->winner = $rsvps->random(1)->first();
    }

    private function openWinnerPhoto(): void
    {
        if ($photo = $this->winner['member']['photo']['photo_link']) {
            exec(sprintf('xdg-open %s', $photo));
        }
    }
}
