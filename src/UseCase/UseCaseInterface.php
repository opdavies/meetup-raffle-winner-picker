<?php

declare(strict_types=1);

namespace App\UseCase;

use App\ValueObject\Result;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface UseCaseInterface
{

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        int $eventId
    );

    public function __invoke(): Result;
}
