<?php

declare(strict_types=1);

namespace App\Command;

use App\Collection\RsvpCollection;
use App\UseCase\FindTheWinner;
use App\ValueObject\Winner;
use DateInterval;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetRaffleWinnerCommand extends Command
{

    protected static $defaultName = 'app:get-raffle-winner';

    private HttpClientInterface $client;

    private CacheInterface $cache;

    private array $eventData = [];

    private Collection $rsvps;

    private Collection $yesRsvps;

    private ?array $winner;

    public function __construct(
        HttpClientInterface $client,
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

        $result = (new FindTheWinner(
            $this->client,
            $this->cache,
            $eventId
        ))->__invoke();

        $event = $result->getEvent();
        $io->title($event->getName());
        $io->text($event->getLink());

        $io->section(
            sprintf(
                '%s \'yes\' RSVPs (excluding hosts)',
                $result->getRsvps()->count()
            )
        );

        $io->listing($result->getRsvps()->getNames()->toArray());

        $io->writeln(
            sprintf('Winner: %s', $result->getWinner()->getName())
        );

        $this->openWinnerPhoto($result->getWinner(), $io);

        return 0;
    }

    private function openWinnerPhoto(Winner $winner, SymfonyStyle $io): void
    {
        if ($photo = $winner->getPhoto()) {
            $io->write($photo);
        }
    }
}
