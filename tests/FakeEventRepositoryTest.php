<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FakeEventRepositoryTest extends KernelTestCase
{
    /** @test */
    public function should_only_return_attendees_with_a_yes_rsvp(): void {
        $repository = new FakeEventRepository();
        $attendees = $repository->getConfirmedAttendees();

        $this->assertCount(3, $attendees->pluck('name'));
    }
}
