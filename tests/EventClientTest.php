<?php

namespace App\Tests;

use App\Collection\EventCollection;
use App\Service\Client\EventClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group api
 */
class EventClientTest extends KernelTestCase
{

    private const EVENT_ID = 270165915;

    private EventClient $client;

    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->client = self::$container->get(EventClient::class);
    }

    public function testRetrievesEventAndGroupData()
    {
        $response = $this->client->getEventData(self::EVENT_ID);

        tap(
            $response['eventData'],
            function (array $eventData): void {
                $this->assertSame('270165915', $eventData['id']);
                $this->assertSame(
                    'Laravel base setup & Modern monoliths with Livewire',
                    $eventData['name']
                );
                $this->assertSame(
                    'PHP South Wales',
                    $eventData['group']['name']
                );
            }
        );
    }

    public function testRetrievesRsvps()
    {
        $response = $this->client->getEventData(self::EVENT_ID);

        $this->assertInstanceOf(EventCollection::class, $response['rsvps']);
        $rsvp = tap(
            $response['rsvps']->first(),
            function (array $rsvp): void {
                $this->assertIsString($rsvp['response']);
            }
        );

        tap(
            $rsvp['event'],
            function (array $event): void {
                $this->assertIsString($event['id']);
                $this->assertIsString($event['name']);
                $this->assertIsInt($event['yes_rsvp_count']);
            }
        );

        tap(
            $rsvp['member'],
            function (array $member): void {
                $this->assertIsInt($member['id']);
                $this->assertIsString($member['name']);
                $this->assertIsArray($member['photo']);
            }
        );

        tap(
            $rsvp['member']['event_context'],
            function (array $eventContext): void {
                $this->assertIsBool($eventContext['host']);
            }
        );
    }
}
