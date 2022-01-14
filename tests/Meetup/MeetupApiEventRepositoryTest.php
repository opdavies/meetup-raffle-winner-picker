<?php

namespace App\Tests\Meetup;

use App\EventRepository;
use App\Tests\EventRepositoryContractTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group api
 */
final class MeetupApiEventRepositoryTest extends KernelTestCase
{
    use EventRepositoryContractTest;

    private EventRepository $repository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->repository = self::$container->get(EventRepository::class);
    }
}
