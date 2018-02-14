@extends('layouts.master')
@section('title', 'Available Apps')

@section('content')
    <md-whiteframe md-elevation="4">
        <md-list>
            @foreach ($apps as $apk)
                <md-list-item md-expand-multiple>
                    <span class="truncate-longer"><img src="{{ asset('images/' . $apk->package_name . '.png') }}" class="app-icon">{{ $apk->name }}
                        <span class="md-hide-small">[{{ $apk->original_title }}]</span></span>
                    <md-layout md-align="end">
                        <span class="md-hide-small muted">{{ $apk->latestApp()->updated_at->diffForHumans() }}&nbsp;</span><span>~ v{{ $apk->latestApp()->version_name ?? 'N/A' }}</span>
                    </md-layout>
                    <md-list-expand>
                        @foreach ($apk->availableApps()->get() as $availableApp)
                            <md-list-item class="md-inset">
                                <md-tooltip md-direction="top">SHA1: {{ $availableApp->hash }}
                                    <br/> Downloaded on: {{ $availableApp->updated_at }} JST ({{ $availableApp->updated_at->diffForHumans() }})
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
    <md-layout>
        <p>Scheduler last run: <span class="muted">{{ timestamp_format(cache('scheduler:last-run'))->diffForHumans() }}</span><br/>#{{ git()->hash() }} (r{{ git()->revision() }})</p>
    </md-layout>
@endsection
