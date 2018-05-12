@extends('ng-layouts.master')
@section('title', 'Short Links')

@section('content')
    <h2 class="md-headline">Short Links</h2>
    <p>Links here will always redirect you to the latest app available. For nerds on mobile. </p>
    <md-list class="md-double-line">
        <div class="md-layout md-layout-nowrap md-gutter md-alignment-center">
            @foreach ($apps as $availableApp)
                <md-list-item class="md-layout-item md-xlarge-size-25 md-large-size-33 md-small-size-50 md-xsmall-size-100">
                    <md-avatar><img src="{{ $availableApp->image }}"></md-avatar>
                    <div class="md-list-item-text">
                        <span>{{ $availableApp->name }}</span>
                        <span><a href="{{ route('links.show', $availableApp->slug) }}">({{ route('links.show', $availableApp->slug) }})</a></span>
                    </div>
                </md-list-item>
            @endforeach
        </div>
    </md-list>
@endsection
