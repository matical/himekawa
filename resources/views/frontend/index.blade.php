@extends('layouts.master')
@section('title', 'Available Apps')

@section('content')
    <md-list>
        @foreach ($apps as $app)
            <md-list-item title="{{ $app->name }}">
                <span>{{ $app->original_title }}</span>
                <md-layout md-align="end">
                    <span>Latest Version: {{ $app->latestApp()->version_name ?? 'N/A' }} </span>
                </md-layout>
                <md-list-expand>
                    <md-list>
                        <md-list-item class="md-inset"><span class="muted">{{ $app->original_title }}</span>
                        </md-list-item>
                    </md-list>
                </md-list-expand>
            </md-list-item>

        @endforeach
    </md-list>
@endsection
