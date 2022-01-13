<?php

namespace App\Tests;

use App\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FakeEventRepositoryTest extends KernelTestCase
{
    /** @test */
    public function should_only_return_attendees_with_a_yes_rsvp(): void {
        $container = self::bootKernel()->getContainer();
        $repository = $container->get(EventRepository::class);

        $attendees = $repository->getConfirmedAttendees();

        $this->assertCount(3, $attendees->pluck('name'));
    }
}
