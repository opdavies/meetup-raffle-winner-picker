<?php

namespace App\Tests;

use App\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FakeEventRepositoryTest extends KernelTestCase
{
    private EventRepository $repository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->repository = static::$container->get(EventRepository::class);
    }

    /** @test */
    public function should_only_return_attendees_with_a_yes_rsvp(): void {

        $attendees = $this->repository->getConfirmedAttendees();

        $this->assertCount(3, $attendees->pluck('name'));
    }
}
