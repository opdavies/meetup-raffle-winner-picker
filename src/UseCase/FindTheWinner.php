<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Collection\RsvpCollection;
use App\ValueObject\Event;
use App\ValueObject\Result;
use App\ValueObject\Winner;
use DateInterval;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class FindTheWinner implements UseCaseInterface
{

    private int $eventId;

    private HttpClientInterface $client;

    private CacheInterface $cache;

    private RsvpCollection $yesRsvps;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        int $eventId
    ) {
        $this->eventId = $eventId;
        $this->client = $client;
        $this->cache = $cache;
    }

    public function __invoke(): Result
    {
        $eventData = $this->retrieveEventData();
        $rsvps = $this->retrieveRsvps();
        $winner = $this->pickWinner($rsvps);

        return new Result(
            Winner::createFromArray($winner),
            Event::createFromArray($eventData),
            $rsvps
        );
    }

    private function retrieveEventData(): array
    {
        $eventData = $this->cache->getItem(sprintf('event.%d', $this->eventId));

        if (!$eventData->isHit()) {
            $response = $this->client->request(
                'GET',
                sprintf(
                    'https://api.meetup.com/%s/events/%d',
                    'php-south-wales',
                    $this->eventId
                )
            );

            $eventData->expiresAfter(
                DateInterval::createFromDateString('1 hour')
            );
            $return = $response->toArray();
            $this->cache->save($eventData->set($return));

            return $return;
        } else {
            return $eventData->get();
        }
    }

    private function retrieveRsvps(): RsvpCollection
    {
        $rsvps = $this->cache->getItem(sprintf('rsvps.%d', $this->eventId));

        if (!$rsvps->isHit()) {
            $response = $this->client->request(
                'GET',
                sprintf(
                    'https://api.meetup.com/%s/events/%d/rsvps',
                    'php-south-wales',
                    $this->eventId
                )
            );

            $filteredRsvps = RsvpCollection::make($response->toArray())
                ->excludeEventHosts()
                ->onlyAttending();

            $rsvps->expiresAfter(DateInterval::createFromDateString('1 hour'));
            $this->cache->save($rsvps->set($filteredRsvps));

            return $filteredRsvps;
        } else {
            return $rsvps->get();
        }
    }

    private function pickWinner(RsvpCollection $rsvps): array
    {
        return $rsvps->random(1)->first()['member'];
    }
}
