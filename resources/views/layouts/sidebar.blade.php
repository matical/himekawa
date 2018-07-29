@include('layouts.header')

<md-app-drawer :md-active.sync="menuVisible">
    <md-toolbar class="md-transparent md-elevation-0">
        <span>hime#{{ git()->hash() }} (r{{ git()->revision() }})</span>

        <div class="md-toolbar-section-end">
            <md-button class="md-icon-button md-dense" @click="toggleMenu">
                <md-icon>keyboard_arrow_left</md-icon>
            </md-button>
        </div>
    </md-toolbar>

    <md-list>
        <md-list-item href="{{ route('index') }}">Available Apps</md-list-item>
        <md-list-item href="{{ route('index.faq') }}">FAQ</md-list-item>
        <md-list-item href="{{ route('links.index') }}">Short Links</md-list-item>
    </md-list>
</md-app-drawer>
