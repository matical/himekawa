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
                        @if ($latestApp = $apk->latestApp())
                            <span class="md-hide-small muted {{ $latestApp->updated_at->diffInHours() < 24 ? 'recently-updated' : '' }}">{{ $latestApp->updated_at->diffForHumans() }}&nbsp;</span>
                            <span class="md-hide-small">~</span>
                            <span>&nbsp;v{{ $latestApp->version_name ?? 'N/A' }}</span>
                        @endif
                    </md-layout>
                    <md-list-expand>
                        @foreach ($apk->availableApps()->get() as $availableApp)
                            <md-list-item class="md-inset">
                                <md-tooltip md-direction="top">SHA1: {{ $availableApp->hash }}
                                    <br/> Downloaded on: {{ $availableApp->updated_at }} JST ({{ $availableApp->updated_at->diffForHumans() }})
                                </md-tooltip>
                                <div class="{{ $loop->first ? '' : 'muted' }}">
                                    <span class="truncate">{{ sprintf('%s.%s.apk', $availableApp->watchedBy->package_name, $availableApp->version_code) }}</span>
                                    <span>(v{{ $availableApp->version_name }})</span>
                                </div>
                                <md-layout md-align="end">
                                    <md-button class="md-raised md-accent {{ $loop->first ? 'button-download' : 'button-download-old' }}" href="{{ apkPath($availableApp->watchedBy->package_name, $availableApp->version_code) }}">
                                        <md-layout class="md-hide-medium-and-up">
                                            <md-icon class="download">file_download</md-icon>
                                        </md-layout>
                                        <md-layout class="md-hide-small">Download</md-layout>
                                    </md-button>
                                </md-layout>
                            </md-list-item>
                        @endforeach
                    </md-list-expand>
                </md-list-item>
            @endforeach
        </md-list>
    </md-whiteframe>
    <md-layout>
        <p>Scheduler last run:
            <a href="{{ route('index.faq') }}">{{ cache('scheduler:last-run') ? timestamp_format(cache('scheduler:last-run'))->diffForHumans() : 'Has not yet run' }}</a>
            <br/>
            #{{ git()->hash() }} (r{{ git()->revision() }})</p>
    </md-layout>
@endsection
