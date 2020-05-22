<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Collection\RsvpCollection;
use Tightenco\Collect\Support\Collection;

final class Result
{
    private Winner $winner;

    private Event $event;

    private Collection $rsvps;

    public function __construct(
        Winner $winner,
        Event $event,
        RsvpCollection $rsvps
    ) {
        $this->winner = $winner;
        $this->event = $event;
        $this->rsvps = $rsvps;
    }

    public function getWinner(): Winner
    {
        return $this->winner;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getRsvps(): RsvpCollection
    {
        return $this->rsvps;
    }
}
