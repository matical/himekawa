<template>
    <md-list>
        <available-app v-for="app in apps" :key="app.id" :app="app"></available-app>
    </md-list>
</template>

<script>
import availableApp from './AvailableApp';

export default {
    props: ['availableApps'],
    components: {
       availableApp,
    },
    data() {
        return {
            apps: {},
        }
    },
    created() {
        this.apps = this.availableApps.filter(app => app.available_apps.length > 0);
    }
};
</script>
