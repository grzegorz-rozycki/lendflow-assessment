<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\IsbnRule;
use Illuminate\Foundation\Http\FormRequest;

final class BestSellersGetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => ['nullable', 'string'],
            'isbn' => ['nullable', 'array'],
            'isbn.*' => [new IsbnRule()],
            'title' => ['nullable', 'string'],
            'offset' => ['nullable', 'numeric', 'min:0', 'multiple_of:20'],
        ];
    }
}
