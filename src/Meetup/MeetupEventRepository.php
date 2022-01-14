<?php

namespace App\Meetup;

use App\EventRepository;
use Illuminate\Support\Collection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MeetupEventRepository implements EventRepository
{
    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }

    public function findAttendeesForEvent(): Collection
    {
        $response = $this->client->request('GET', 'https://api.meetup.com/php-south-wales/events/282265786/rsvps');

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
