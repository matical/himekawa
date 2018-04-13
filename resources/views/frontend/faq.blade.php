@extends('layouts.master')
@section('title', 'FAQ')

@section('content')
    <div class="container__faq">
        <h2 class="heavy">Frequency</h2>
        <p>The scraper runs every 15 minutes, from {{ config('himekawa.scheduler.start_time') }} to {{ config('himekawa.scheduler.end_time') }} JST.</p>

        <h2 class="heavy">Hashes</h2>
        <p>Every hash (SHA1) displayed on each file is retrieved from GPlay (included in the payload metadata). The scraper will automatically verifies each downloaded file; if there is a hash mismatch, the file is automatically discarded. (that means if it doesn't verify something is borked -- let me know thanks) </p>

        <h2 class="heavy">APK Lifetime</h2>
        <p>The system will keep up to 5 APKs per game.</p>

        <h2 class="heavy">Telegram Bot Updates</h2>
        <p>Join <a href="https://t.me/joinchat/AAAAAEwtuc6mq_TfZAzviA">this</a> telegram channel if you want to receive updates everytime an app here is updated.</p>

        <h2 class="heavy">Adding APKs</h2>
        <p>As long as it's a weeb (JP region-locked) game, just drop me a message and I'll add it.</p>

        <h2 class="heavy">Contact/Feedback/If something breaks</h2>
        <p>kannazuki/matic@rizon ~ あまつか#7851 ~ <a href="mailto:mao@amatsuka.me">Email</a></p>
    </div>
@endsection
