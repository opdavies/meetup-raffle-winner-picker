<?php

namespace App\Tests;

use App\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FakeEventRepositoryTest extends KernelTestCase
{
    use EventRepositoryContractTest;

    private EventRepository $repository;

    public function setUp(): void
    {
        self::bootKernel();

        $container = static::$container;

        $container->set(EventRepository::class, new FakeEventRepository());

        $this->repository = static::$container->get(EventRepository::class);
    }
}
