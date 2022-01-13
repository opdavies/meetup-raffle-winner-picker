<?php

namespace App\Tests;

use App\EventRepository;

trait EventRepositoryContractTest
{
    private EventRepository $repository;

    /** @test */
    public function should_only_return_attendees_with_a_yes_rsvp(): void
    {
        $attendees = $this->repository->getConfirmedAttendees();

        $this->assertCount(3, $attendees);
    }

    /** @test */
    public function should_not_return_event_organisers(): void
    {
        $attendees = $this->repository->getConfirmedAttendees();

        $this->assertCount(3, $attendees);
    }
}
