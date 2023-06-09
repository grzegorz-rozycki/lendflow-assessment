<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Controllers\BestSellerEndpointController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class BestSellerEndpointTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    public function testEndpointReturnsOk(): void
    {
        $this
            ->getJson('/api/1/nyt/best-sellers')
            ->assertOk();
    }

    public function testEndpointFilterAuthor(): void
    {
        $author = $this->faker->name;
        $this
            ->getJson('/api/1/nyt/best-sellers?author=')
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?author=' . $author)
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?author[]=' . $author)
            ->assertJsonValidationErrorFor('author');
    }

    public function testEndpointFilterIsbn(): void
    {
        $isbn10 = $this->faker->isbn10();
        $isbn13 = $this->faker->isbn13();
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn=')
            ->assertOk();
        $this
            ->getJson("/api/1/nyt/best-sellers?isbn[]=$isbn10&isbn[]=$isbn13")
            ->assertOk();

        // invalid length
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn[]=' . str_repeat('0', 1))
            ->assertJsonValidationErrorFor('isbn.0');
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn[]=' . str_repeat('0', 8))
            ->assertJsonValidationErrorFor('isbn.0');
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn[]=' . str_repeat('0', 12))
            ->assertJsonValidationErrorFor('isbn.0');
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn[]=' . str_repeat('0', 14))
            ->assertJsonValidationErrorFor('isbn.0');

        // invalid content but length ok
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn[]=' . str_repeat('a', 10))
            ->assertJsonValidationErrorFor('isbn.0');
        $this
            ->getJson('/api/1/nyt/best-sellers?isbn[]=111-111-1111')
            ->assertJsonValidationErrorFor('isbn.0');
    }

    public function testEndpointFilterTitle(): void
    {
        $title = $this->faker->sentence;
        $this
            ->getJson('/api/1/nyt/best-sellers?title=')
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?title=' . $title)
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?title[]=' . $title)
            ->assertJsonValidationErrorFor('title');
    }

    public function testEndpointFilterOffset(): void
    {
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=')
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=0')
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=20')
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=100')
            ->assertOk();
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=' . (random_int(1, 100) * 20))
            ->assertOk();

        // not an array
        $this
            ->getJson('/api/1/nyt/best-sellers?offset[]=0')
            ->assertJsonValidationErrorFor('offset');

        // invalid values
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=-10')
            ->assertJsonValidationErrorFor('offset');
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=-10')
            ->assertJsonValidationErrorFor('offset');
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=1')
            ->assertJsonValidationErrorFor('offset');
        $this
            ->getJson('/api/1/nyt/best-sellers?offset=9')
            ->assertJsonValidationErrorFor('offset');
    }

    public function testEndpointPassesQueryParameters(): void
    {
        $author = $this->faker->name;
        $title = $this->faker->sentence;
        $isbn10 = $this->faker->isbn10();
        $isbn13 = $this->faker->isbn13();
        $offset = random_int(1, 100) * 20;
        $queryData = compact('author', 'title', 'offset');
        $queryData['isbn'] = [$isbn10, $isbn13];
        $this
            ->getJson('/api/1/nyt/best-sellers?' . http_build_query($queryData))
            ->assertOk();

        $forwardedQueryData = $queryData;
        $forwardedQueryData['isbn'] = "$isbn10;$isbn13";
        $forwardedQueryData['api-key'] = 'fake-token';
        $expectedUrl = BestSellerEndpointController::ENDPOINT_URL . '?' . http_build_query($forwardedQueryData);

        Http::assertSent(static fn(Request $request) => $request->url() === $expectedUrl);
    }
}
