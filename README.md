# Meetup.com Raffle Winner Picker

A Symfony console application used by the [PHP South Wales user group](https://www.phpsouthwales.uk) to select raffle prize winners from the attendees for an event.

## Usage

Run the `meetup:pick-winner` command to retrieve the 'yes' RSVPs from Meetup for an event, and select a winner at random. The only argument is the Meetup event ID, which is required.

    ./run console meetup:pick-winner <event_id>

RSVPs by event hosts (i.e. organisers) are automatically removed so that they are not returned.
