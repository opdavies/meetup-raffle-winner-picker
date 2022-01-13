<?php

namespace App;

use Illuminate\Support\Collection;

interface AttendeeLoader
{
    public function getAttendees(): Collection;
}
