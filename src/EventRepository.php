<?php

namespace App;

use Illuminate\Support\Collection;

interface EventRepository
{
    public function findAttendeesForEvent(): Collection;
}
