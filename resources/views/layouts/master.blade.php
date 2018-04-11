<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ff9800">

    <meta name="description" content="@yield('meta-description', 'ほ？')">

    <title>ひめかわ :: @yield('title')</title>

    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,400italic">
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">

    {{-- Styles --}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" v-cloak>
    <div class="container">
        @include('layouts.sidebar')
        @include('layouts.header')
        <div class="main-content">
            @yield('content')
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
