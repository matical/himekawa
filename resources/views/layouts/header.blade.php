<md-app-toolbar class="himekawa" :md-elevation="0">
    <md-button class="md-icon-button" @click="toggleMenu">
        <md-icon>menu</md-icon>
    </md-button>
    <span class="md-title">@yield('title', 'himekawa')</span>
</md-app-toolbar>
