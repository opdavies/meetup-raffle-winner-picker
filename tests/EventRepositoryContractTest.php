<?php

namespace App\Tests;

use App\EventRepository;
use Tightenco\Collect\Support\Collection;

trait EventRepositoryContractTest
{
    private EventRepository $repository;

    /** @test */
    public function should_only_return_attendees_with_a_yes_rsvp(): void
    {
        $attendees = $this->repository->findAttendeesForEvent();

        $this->assertOnlyAttendingAttendeesAreReturned($attendees);
    }

    /** @test */
    public function should_not_return_event_organisers(): void
    {
        $attendees = $this->repository->findAttendeesForEvent();

        $this->assertEventHostsAreNotReturned($attendees);
    }

    private function assertEventHostsAreNotReturned(Collection $attendees): void
    {
        $this->assertSame([false], $attendees->pluck('is_host')->unique()->toArray());
    }

    private function assertOnlyAttendingAttendeesAreReturned(Collection $attendees): void
    {
        $this->assertFalse($attendees->pluck('is_attending')->contains(false));
    }
}
