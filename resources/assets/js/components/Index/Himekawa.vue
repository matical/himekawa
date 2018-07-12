<template>
    <md-list>
        <app-item v-for="app in availableApps" :key="app.id" :app="app"></app-item>
    </md-list>
</template>

<script>
    import availableApp from './AvailableApp';

    export default {
        props: ['availableApps'],
        components: {
            'app-item': availableApp
        }
    }
</script>

