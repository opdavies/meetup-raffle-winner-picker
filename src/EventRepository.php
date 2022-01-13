<?php

namespace App;

interface EventRepository
{
    public function getConfirmedAttendees(): array;
}
