@extends('layouts.master')
@section('title', 'Available Apps')
@section('meta-description', 'APKs for weeb games.')

@section('content')
    @if (announcement()->available())
        <div class="s-container">
            <h3 class="med"><a href="{{ route('index.cd') }}">💿 Announcement</a></h3>
        </div>
    @endif
    <himekawa :available-apps='@json($apps)'></himekawa>
    <div class="s-container">
        <span>Last check:</span>
        <a href="{{ route('index.faq') }}">{{ lastRun()->lastCheck() ? lastRun()->lastCheck()->diffForHumans() : 'N/A' }}</a>
        <br>
        <span>Exceptions last week: <span class="muted">{{ app('yuki\Repositories\ExceptionRepository')->numberOfExceptions() }}</span></span>
    </div>
@endsection
