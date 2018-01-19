<md-sidenav class="md-left main-sidebar md-fixed" md-swipeable ref="leftSidenav">
    <md-toolbar class="md-large">
        <div class="md-toolbar-container">
            <h3 class="md-title">{{ config('app.name', 'ひめかわ') }}</h3>
        </div>
    </md-toolbar>
    <md-list>
        <md-list-item>
            <a href="{{ route('index') }}">Available Apps</a>
        </md-list-item>
        <md-list-item>
            <a href="{{ route('index.faq') }}">FAQ</a>
        </md-list-item>
        <md-list-item>
            <a href="{{ route('links.index') }}">Magic Links</a>
        </md-list-item>
    </md-list>
</md-sidenav>
