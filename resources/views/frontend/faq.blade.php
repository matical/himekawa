@extends('layouts.master')
@section('title', 'FAQ')

@section('content')
    <h2>Frequency</h2>
    <p>The scraper runs every 15 minutes. Only on weekdays from {{ config('himekawa.scheduler.start_time') }} to {{ config('himekawa.scheduler.end_time') }} JST.</p>

    <h2>Hashes</h2>
    <p>Every hash (SHA1) displayed on each file is retrieved from GPlay (included in the payload metadata). The scraper automatically verifies each downloaded file; if there is a hash mismatch, the file is automatically discarded. (that means if it doesn't verify it's borked -- let me know thanks) </p>

    <h2>APK Lifetime</h2>
    <p>The system will keep up to 5 APKs per game.</p>

    <h2>Telegram Bot Updates</h2>
    <p>Join <a href="https://t.me/joinchat/AAAAAEwtuc6mq_TfZAzviA">this</a> telegram channel if you want to receive updates everytime an app here is updated.</p>

    <h2>Adding APKs</h2>
    <p>As long as it's a weeb (JP region-locked) game, just drop me a message and I'll add it.</p>

    <h2>Contact/Feedback/If something breaks</h2>
    <p>kannazuki/matic@rizon | あまつか#7851 | <a href="mailto:mao@amatsuka.me">Email</a></p>
@endsection
