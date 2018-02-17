@extends('layouts.master')
@section('title', 'Short Links')

@section('content')
    <h2>Magic Links</h2>
    <p>Links here will always redirect you to the latest app available. For nerds on mobile. </p>
    <ul>
        @foreach ($apps as $availableApp)
            <li>{{ $availableApp->name }} <a href="l/{{ $availableApp->slug }}">(l/{{ $availableApp->slug }})</a></li>
        @endforeach
    </ul>
@endsection
