<?php

declare(strict_types=1);

namespace App\Command;

use App\EventRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GetRaffleWinnerCommand extends Command
{
    protected static $defaultName = 'meetup:pick-winner';

    private EventRepository $eventRepository;

    public function __construct(
        EventRepository $eventRepository,
        string $name = null
    ) {
        parent::__construct($name);

        $this->eventRepository = $eventRepository;
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

        $attendees = $this->eventRepository->findAttendeesForEvent($eventId);

        $winner = $attendees->random();

        $io->title('Meetup Raffle Winner Picker');

        $io->text(sprintf('"Yes" RSVPs (%d):', $attendees->count()));
        $io->newLine();

        // TODO: this is meetup specific, so needs to be made agnostic.
        $io->listing($attendees->map->member->map->name->toArray());

        $io->success($winner->member->name);

        return 0;
    }
}
