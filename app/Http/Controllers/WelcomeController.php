<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Mail\Markdown;

final class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        $content = Markdown::parse(file_get_contents(base_path('README.md')));
        return view('welcome', compact('content'));
    }
}
