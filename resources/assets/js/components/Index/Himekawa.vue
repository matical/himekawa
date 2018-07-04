<template>
    <md-list>
        <app-item v-for="app in availableApps" :key="app.id" :app="app"></app-item>
    </md-list>
</template>

<script>
    export default {
        props: ['availableApps'],
    }
</script>

