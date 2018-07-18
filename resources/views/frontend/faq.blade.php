@extends('layouts.master')
@section('title', 'FAQ')

@section('content')
    <div class="s-container">
        <h2 class="md-title">Frequency</h2>
        <p>The scraper runs every 15 minutes, from {{ config('himekawa.scheduler.start_time') }} to {{ config('himekawa.scheduler.end_time') }} JST.</p>

        <h2 class="md-title">Hashes</h2>
        <p>Hashes are fetched directly from Google Play's servers. The scraper will automatically discard files that don't verify so hopefully </p>

        <h2 class="md-title">APK Lifetime</h2>
        <p>The system will keep up to 5 APKs per game.</p>

        <h2 class="md-title">Notifications</h2>
        <p><a href="https://t.me/joinchat/AAAAAEwtuc6mq_TfZAzviA">Telegram Channel</a> | <a href="{{ route('feeds.main') }}">RSS Feed</a></p>

        <h2 class="md-title">Adding APKs</h2>
        <p>As long as it's a weeb (JP region-locked) game, just drop me a message and I'll add it.</p>

        <h2 class="md-title">Contact/Feedback/If something breaks</h2>
        <p>kannazuki/matic@rizon | Discord: あまつか#7851</p>
    </div>
@endsection
