<?php

namespace App\Tests;

use App\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group api
 */
final class MeetupEventRepositoryTest extends KernelTestCase
{
    use EventRepositoryContractTest;

    private EventRepository $repository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->repository = self::$container->get(EventRepository::class);
    }
}
