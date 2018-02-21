@extends('layouts.error')

@section('title', 'Upgrading')

@section('message')
    <span>Be right back.</span>
    <br>
    <span>{{ $exception->getMessage() }}</span>
    <br>
    <span>Check back in {{ $exception->retryAfter }}s or so.<span>
@endsection
