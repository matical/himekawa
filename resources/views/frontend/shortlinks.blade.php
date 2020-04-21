@extends('layouts.master')
@section('title', 'Short Links')

@section('content')
    <div class="s-container">
        <h2 class="md-headline">Short Links</h2>
        <p>Links here will always redirect you to the latest available APK version.</p>
        <md-list class="md-double-line">
            <yuki :watched-apps='@json($apps)'></yuki>
        </md-list>
    </div>
@endsection
