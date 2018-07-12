<template>
    <md-list-item md-expand @mouseover="hovered = true" @mouseleave="hovered = false">
        <md-avatar>
            <img :src="app.image">
        </md-avatar>
        <div class="md-list-item-text">
            <span>{{ app.name }}</span>
            <span class="md-small-hide muted">v{{ latestApp.version_name }}</span>
            <span>
                    <span :class="{recent: isRecent(latestApp.created_at)}">{{ diffDate(latestApp.created_at) }}</span>
                    <transition name="fade">
                        <span v-if="hovered">/ {{ formatPrettyDate(latestApp.created_at) }}</span>
                    </transition>
            </span>
        </div>

        <md-list slot="md-expand">
            <apk v-for="(apk, index) in app.available_apps"
                 :key="apk.version_code"
                 :apk="apk"
                 :package-name="app.package_name"
                 :index="index"
            ></apk>
        </md-list>
    </md-list-item>
</template>

<script>
    import apk from './Apk';

    export default {
        props: ['app'],
        components: {
            'apk': apk
        },
        data() {
            return {
                hovered: false,
                latestApp: this.app.available_apps[0]
            }
        }
    }
</script>

<style lang="scss" scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
