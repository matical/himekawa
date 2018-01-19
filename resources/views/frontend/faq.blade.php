@extends('layouts.master')
@section('title', 'FAQ')

@section('content')
    <h2>Hashes</h2>
    <p>Every hash (SHA1) displayed on each file is retrieved from GPlay (included in the payload metadata). The scraper automatically verifies each downloaded file; if there is a hash mismatch, the file is automatically discarded. (that means if it doesn't verify it's borked -- let me know thanks) </p>

    <h2>APK Lifetime</h2>
    <p>The system will keep up to 5 APKs per game.</p>

    <h2>Feedback/If something breaks</h2>
    <p>kannazuki/matic @ Rizon | <a href="mailto:mao@amatsuka.me">Mail</a></p>
@endsection
