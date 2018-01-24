@extends('layouts.master')
@section('title', 'Available Apps')

@section('content')
    <md-whiteframe md-elevation="4">
        <md-list>
            @foreach ($apps as $app)
                <md-list-item md-expand-multiple>
                    <span class="truncate-longer"><img src="{{ asset('images/' . $app->package_name . '.png') }}" class="app-icon">{{ $app->name }} <span class="md-hide-small">({{ $app->original_title }})</span></span>
                    <md-layout md-align="end">
                        <span>v{{ $app->latestApp()->version_name ?? 'N/A' }} </span>
                    </md-layout>
                    <md-list-expand>
                        @foreach ($app->availableApps()->get() as $availableApp)
                            <md-list-item class="md-inset">
                                <md-tooltip md-direction="top">SHA1: {{ $availableApp->hash }} <br/> Last updated: {{ $app->created_at }} JST
                                </md-tooltip>
                                <span class="truncate">{{ sprintf('%s.%s.apk', $availableApp->watchedBy->package_name, $availableApp->version_code) }}</span>
                                <span>(v{{ $availableApp->version_name }})</span>
                                <md-layout md-align="end">
                                    <md-button class="md-raised md-accent {{ $loop->first ? 'button-download' : 'button-download-old' }}" href="{{ apkPath($availableApp->watchedBy->package_name, $availableApp->version_code) }}">Download</md-button>
                                </md-layout>
                            </md-list-item>
                        @endforeach
                    </md-list-expand>
                </md-list-item>
            @endforeach
        </md-list>
    </md-whiteframe>
    <md-layout md-align="end">
        <p>The scraper runs every 30 minutes, only weekdays from {{ config('himekawa.scheduler.start_time') }} to {{ config('himekawa.scheduler.end_time') }} JST.</p>
    </md-layout>
@endsection
