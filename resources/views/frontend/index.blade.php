@extends('layouts.master')
@section('title', 'Available Apps')

@section('content')
    <md-whiteframe md-elevation="4">
        <md-list>
            @foreach ($apps as $app)
                <md-list-item md-expand-multiple>
                    <span class="truncate-longer"><img src="/images/{{ $app->package_name }}.png" class="app-icon">{{ $app->name }} ({{ $app->original_title }})</span>
                    <md-layout md-align="end">
                        <span>Latest Version: {{ $app->latestApp()->version_name ?? 'N/A' }} </span>
                    </md-layout>
                    <md-list-expand>
                        <md-list>
                            @foreach ($app->availableApps()->get() as $availableApp)
                                <md-list-item class="md-inset" title="{{ $app->package_name }}">
                                    <span class="truncate">{{ sprintf('%s.%s.apk', $availableApp->watchedBy->package_name, $availableApp->version_code) }}</span>
                                    <span>(v{{ $availableApp->version_name }})</span>
                                    <md-layout md-align="end">
                                        <md-button class="md-raised md-accent {{ $loop->first ? 'button-download' : 'button-download-old' }}" href="{{ apkPath($availableApp->watchedBy->package_name, $availableApp->version_code) }}">Download</md-button>
                                    </md-layout>
                                </md-list-item>
                            @endforeach
                        </md-list>
                    </md-list-expand>
                </md-list-item>
            @endforeach
        </md-list>
    </md-whiteframe>
@endsection
