@extends('layouts.master')
@section('title', 'Available Apps')
@section('meta-description', 'APKs for weeb games.')

@section('content')
    <div class="s-container">
        <h3 class="med"><a href="{{ route('index.faq') }}">💿: announcement thingy</a></h3>
    </div>
    <himekawa :available-apps='@json($apps)'></himekawa>
    <div class="s-container">
        Last check:
        <a href="{{ route('index.faq') }}">{{ lastRun()->lastCheck() ? lastRun()->lastCheck()->diffForHumans() : 'N/A' }}</a>
        <br>
        &gt;&gt;<a href="{{ route('index.faq') }}"> FAQ</a>
    </div>
@endsection
