<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BestSellersGetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

final class BestSellerEndpointController extends Controller
{
    public const ENDPOINT_URL = 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json';

    private const ENDPOINT_TIMEOUT = 5;

    public function __invoke(BestSellersGetRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $requestData['api-key'] = config('services.nyt.api_key');
        $requestData['isbn'] = implode(';', $requestData['isbn'] ?? []);
        $queryString = http_build_query(array_filter($requestData));
        $response = Http::asJson()
            ->timeout(self::ENDPOINT_TIMEOUT)
            ->get(sprintf('%s?%s', self::ENDPOINT_URL, $queryString));

        return new JsonResponse($response->json(), $response->status());
    }
}
