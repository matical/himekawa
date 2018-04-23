@extends('layouts.master')
@section('title', 'Available Apps')
@section('meta-description', 'APKs for weeb games.')

@section('content')
    <md-whiteframe md-elevation="4">
        <md-list>
            @foreach ($apps as $apk)
                <md-list-item md-expand-multiple>
                    <span class="truncate-longer">
                        <md-avatar>
                            <img src="{{ $apk->image }}"/>
                        </md-avatar>
                        <span>{{ $apk->name }}</span>
                        <span class="md-hide-small muted">[{{ $apk->original_title }}]</span>
                    </span>
                    <md-layout md-align="end">
                        @if ($latestApp = $apk->availableApps->first())
                            @if ($latestApp->created_at->diffInDays() < 2)
                                <span class="md-hide-medium-and-up"><md-icon class="recently-updated">new_releases</md-icon></span>
                                <span class="md-hide-small recently-updated">{{ $latestApp->created_at->diffForHumans() }}&nbsp;</span>
                                <span class="md-hide-small muted">-</span>
                                <span>&nbsp;v{{ $latestApp->version_name ?? 'N/A' }}</span>
                            @else
                                <span class="md-hide-small muted">{{ $latestApp->created_at->diffForHumans() }}&nbsp;</span>
                                <span class="md-hide-small">-</span>
                                <span>&nbsp;v{{ $latestApp->version_name ?? 'N/A' }}</span>
                            @endif
                        @endif
                    </md-layout>
                    <md-list-expand>
                        @foreach ($apk->availableApps as $availableApp)
                            <md-list-item class="md-inset">
                                <md-tooltip md-direction="top">SHA1: {{ $availableApp->hash }}
                                    <br> Downloaded on: {{ $availableApp->created_at }} JST ({{ $availableApp->created_at->diffForHumans() }})
                                </md-tooltip>
                                <md-whiteframe md-elevation="2">
                                    <span class="tags {{ $loop->first ? 'latest' : 'muted' }}">v{{ $availableApp->version_name }}</span>
                                </md-whiteframe>
                                <span class="truncate {{ $loop->first ? '' : 'muted' }}">{{ buildApkFilename($apk->package_name, $availableApp->version_code) }}</span>
                                <md-layout md-align="end">
                                    <md-button class="md-raised md-accent {{ $loop->first ? 'button-download' : 'button-download-old' }}" href="{{ apkPath($apk->package_name, $availableApp->version_code) }}">
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
        <p>
            Last check: <a href="{{ route('index.faq') }}">{{ lastRun()->lastCheck() ? lastRun()->lastCheck()->diffForHumans() : 'N/A' }}</a>,
            Last update: <a href="{{ route('index.faq') }}">{{ lastRun()->lastUpdate() ? lastRun()->lastUpdate()->diffForHumans() : 'N/A' }}</a>
            <br>
            hime#{{ git()->hash() }} (r{{ git()->revision() }})
            <br>
            <a href="{{ route('index.faq') }}">&gt;&gt; FAQ</a>
        </p>
    </md-layout>
    @includeWhen(announcement()->available(), 'components.cd', ['announcements' => announcement()->get()])
@endsection
