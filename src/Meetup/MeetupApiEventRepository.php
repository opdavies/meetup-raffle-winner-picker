<?php

namespace App\Meetup;

use App\EventRepository;
use Illuminate\Support\Collection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MeetupApiEventRepository implements EventRepository
{
    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function findAttendeesForEvent(int $eventId): Collection
    {
        $apiUrl = sprintf('https://api.meetup.com/%s/events/%d/rsvps', 'php-south-wales', $eventId);

        $response = $this->client->request('GET', $apiUrl);

        $rsvps = json_decode($response->getContent());

        return Collection::make($rsvps)
            ->filter(fn (\stdClass $rsvp): bool => $rsvp->response == RsvpResponse::RESPONSE_YES)
            ->filter(fn (\stdClass $attendee): bool => !$attendee->member->event_context->host)
            ->map(function (\stdClass $attendee): \stdClass {
                $attendee->is_attending = true;
                $attendee->is_host = false;

                return $attendee;
            })
            ;
    }
}
