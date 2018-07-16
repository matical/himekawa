@extends('layouts.master')
@section('title', 'Available Apps')

@section('content')
    @empty($announcement)
        No announcements available :)
    @else
        <div class="marked">
            {!! $announcement !!}
        </div>
    @endif
@endsection
