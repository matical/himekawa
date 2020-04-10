<template>
    <div class="md-layout md-layout-nowrap md-gutter">
        <single-app v-for="watched in apps" :key="watched.id" :watched-app="watched"></single-app>
    </div>
</template>

<script>
import SingleApp from './SingleApp';

export default {
    props: ['watchedApps'],
    components: { SingleApp },
    data() {
        return {
            apps: {},
            hovered: false,
        };
    },
    created() {
        this.apps = this.watchedApps.filter(app => app.available_apps.length > 0);
    },
};
</script>
