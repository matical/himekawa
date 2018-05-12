@extends('layouts.master')
@section('title', 'FAQ')

@section('content')
    <h2 class="md-title">Frequency</h2>
    <p>The scraper runs every 15 minutes, from {{ config('himekawa.scheduler.start_time') }} to {{ config('himekawa.scheduler.end_time') }} JST.</p>

    <h2 class="md-title">Checks and Updates</h2>
    <p>Checks: last scheduler run. Updates: last time a new APK was added.</p>

    <h2 class="md-title">Hashes</h2>
    <p>Every hash (SHA1) displayed on each file is retrieved from GPlay (included in the payload metadata). The scraper will automatically verifies each downloaded file; if there is a hash mismatch, the file is automatically discarded. (that means if it doesn't verify something is borked -- let me know thanks) </p>

    <h2 class="md-title">APK Lifetime</h2>
    <p>The system will keep up to 5 APKs per game.</p>

    <h2 class="md-title">Notifications</h2>
    <p><a href="https://t.me/joinchat/AAAAAEwtuc6mq_TfZAzviA">Telegram Channel</a> |
        <a href="{{ route('feeds.main') }}">RSS Feed</a></p>

    <h2 class="md-title">Adding APKs</h2>
    <p>As long as it's a weeb (JP region-locked) game, just drop me a message and I'll add it.</p>

    <h2 class="md-title">Contact/Feedback/If something breaks</h2>
    <p>kannazuki/matic@rizon | Discord: あまつか#7851</p>
@endsection
