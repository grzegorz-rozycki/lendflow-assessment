<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BestSellersGetRequest;
use Illuminate\Http\JsonResponse;

final class BestSellerEndpointController extends Controller
{
    public function __invoke(BestSellersGetRequest $request): JsonResponse
    {
        return new JsonResponse();
    }
}
