<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Collection\EventCollection;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tightenco\Collect\Support\Collection;

final class EventClient
{

    private HttpClientInterface $client;

    private array $eventData = [];

    private Collection $rsvps;

    private Collection $yesRsvps;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getEventData(int $eventId): array
    {
        $this->retrieveEventData($eventId);
        $this->retrieveRsvps($eventId);

        $eventData = $this->eventData;
        $rsvps = $this->yesRsvps;

        return compact(
            'eventData',
            'rsvps'
        );
    }

    private function retrieveEventData(int $eventId): void
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                'https://api.meetup.com/%s/events/%d',
                'php-south-wales',
                $eventId
            )
        );

        $this->eventData = $response->toArray();
    }

    private function retrieveRsvps(int $eventId): void
    {
        $response = $this->client->request(
            'GET',
            vsprintf(
                'https://api.meetup.com/%s/events/%d/rsvps',
                [
                    'php-south-wales',
                    $eventId,
                ]
            )
        );

        $this->rsvps = EventCollection::make($response->toArray())
            ->excludeEventHosts();

        $this->yesRsvps = $this->rsvps->filter(
            function (array $rsvp): bool {
                return $rsvp['response'] == 'yes';
            }
        );
    }
}
