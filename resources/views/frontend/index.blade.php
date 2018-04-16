@extends('layouts.master')
@section('title', 'Available Apps')
@section('meta-description', 'APKs for weeb games.')

@section('content')
    <md-whiteframe md-elevation="4">
        <md-list>
            @foreach ($apps as $apk)
                <md-list-item md-expand-multiple>
                    <span class="truncate-longer">
                        <img src="{{ mix("images/{$apk->package_name}.png") }}" class="app-icon"/>
                        {{ $apk->name }}
                        <span class="md-hide-small">[{{ $apk->original_title }}]</span>
                    </span>
                    <md-layout md-align="end">
                        @if ($latestApp = $apk->latestApp())
                            @if ($latestApp->created_at->diffInDays() < 2)
                                <span class="md-hide-medium-and-up"><md-icon class="recently-updated">new_releases</md-icon></span>
                                <span class="md-hide-small muted recently-updated">{{ $latestApp->created_at->diffForHumans() }}&nbsp;</span>
                                <span class="md-hide-small">::</span>
                                <span>&nbsp;v{{ $latestApp->version_name ?? 'N/A' }}</span>
                            @else
                                <span class="md-hide-small muted">{{ $latestApp->created_at->diffForHumans() }}&nbsp;</span>
                                <span class="md-hide-small">::</span>
                                <span>&nbsp;v{{ $latestApp->version_name ?? 'N/A' }}</span>
                            @endif
                        @endif
                    </md-layout>
                    <md-list-expand>
                        @foreach ($apk->availableApps()->get() as $availableApp)
                            <md-list-item class="md-inset">
                                <md-tooltip md-direction="top">SHA1: {{ $availableApp->hash }}
                                    <br/> Downloaded on: {{ $availableApp->created_at }} JST ({{ $availableApp->created_at->diffForHumans() }})
                                </md-tooltip>
                                <span class="truncate {{ $loop->first ? '' : 'muted' }}">{{ sprintf('%s.%s.apk', $availableApp->watchedBy->package_name, $availableApp->version_code) }}</span>
                                <span class="{{ $loop->first ? '' : 'muted' }}">(v{{ $availableApp->version_name }})</span>
                                <md-layout md-align="end">
                                    <md-button class="md-raised md-accent {{ $loop->first ? 'button-download' : 'button-download-old' }}" href="{{ $availableApp->url() }}">
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
            Last check:
            <a href="{{ route('index.faq') }}">{{ lastRun()->lastCheck() ? lastRun()->lastCheck()->diffForHumans() : 'N/A' }}</a>
            <br>
            Last update:
            <a href="{{ route('index.faq') }}">{{ lastRun()->lastUpdate() ? lastRun()->lastUpdate()->diffForHumans() : 'N/A' }}</a>
            <br>
            #{{ git()->hash() }} (r{{ git()->revision() }})
            <br/>
            <a href="{{ route('index.faq') }}">&gt;&gt; FAQ</a>
        </p>
    </md-layout>
    @includeWhen(announcement()->available(), 'components.cd', ['announcements' => announcement()->get()])
@endsection
