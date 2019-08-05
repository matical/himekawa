@extends('layouts.master')
@section('title', 'Available Apps')
@section('meta-description', 'APKs for weeb games.')

@section('content')
    @if (announcement()->available())
        <div class="s-container">
            <h3 class="med">
                <a href="{{ route('index.cd') }}">💿 Announcement - {{ announcement()->announcedOn()->diffForHumans() }}</a>
            </h3>
        </div>
    @endif
    <himekawa :available-apps='@json($apps)'></himekawa>
    <div class="s-container">
        @if (Cache::has('scheduler-disabled'))
            <span>Scheduler is disabled.</span>
        @else
            <span>Last check:</span>
            <a href="{{ route('index.faq') }}">{{ lastRun()->lastCheck() ? lastRun()->lastCheck()->diffForHumans() : 'N/A' }}</a>
        @endif
        <br>
        <span>Exceptions last week: <span class="muted">{{ app('yuki\Repositories\ExceptionRepository')->numberOfExceptions() }}</span></span>
        <br>
        <span>hime {{ git()->prettyVersion() }}</span>
    </div>
@endsection
