<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#d86b0d">

    <meta name="description" content="@yield('meta-description', 'ksmz is mine.')">

    <meta property="og:title" content="ひめかわ :: @yield('title')">
    <meta property="og:description" content="@yield('meta-description', 'ksmz is mine.')">
    <meta property="og:image" content="{{ asset('favicon.png') }}">

    <title>ひめかわ :: @yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,400italic">
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">

    {{-- Styles --}}
    <style>[v-cloak] { display: none; }</style>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @include('feed::links')
</head>
<body>
<div id="app" v-cloak>
    <md-app :md-scrollbar="false">
        @include('layouts.sidebar')
        <md-app-content>
            @yield('content')
        </md-app-content>
    </md-app>
</div>

{{-- Scripts --}}
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
