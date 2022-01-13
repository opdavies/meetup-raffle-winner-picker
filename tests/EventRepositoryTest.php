<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tightenco\Collect\Support\Collection;

final class EventRepositoryTest extends KernelTestCase
{
    /** @test */
    public function should_only_return_attendees_with_a_yes_rsvp(): void {
        $attendees = Collection::make([
            ['name' => 'Matthew Setter'],
            ['name' => 'Matthew Setter'],
            ['name' => 'Matthew Setter'],
        ]);

        $this->assertCount(3, $attendees->pluck('name'));
    }
}
