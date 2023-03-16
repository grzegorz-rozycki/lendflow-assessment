<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        <style>
            body {
                padding: 10px;
                font-family: sans-serif;
            }

            code {
                padding: 0 2px;
                border: solid 1px lightgray;
                border-radius: 4px;
                background: #f3f4f6;
                color: #ec4899;
                font-weight: bold;
            }

            @media screen and (min-width: 768px){
                body {
                    padding: 20px;
                    width: 700px;
                    margin: 0 auto;
                }
            }
        </style>
    </head>
    <body>
        {!! $content ?? '' !!}
    </body>
</html>
