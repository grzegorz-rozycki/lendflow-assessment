<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class BestSellerEndpointTest extends TestCase
{
    public function test_endpoint_returns_ok(): void
    {
        $this
            ->get('/api/1/nyt/best-sellers')
            ->assertOk();
    }
}
