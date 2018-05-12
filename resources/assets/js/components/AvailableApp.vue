<template>
    <md-list-item md-expand @mouseover="hovered = true" @mouseleave="hovered = false">
        <md-avatar>
            <img :src="app.image">
        </md-avatar>
        <div class="md-list-item-text">
            <span>{{ app.name }}</span>
            <span class="md-small-hide muted">{{ app.original_title }}</span>
            <span>
                    {{ formatDate(app.available_apps[0].created_at) }}
                    <transition name="fade">
                        <span v-if="hovered">/ {{ formatPrettyDate(app.available_apps[0].created_at) }}</span>
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
    import moment from "moment";
    import {upperFirst} from "lodash-es";

    export default {
        props: ['app'],
        data() {
            return {
                hovered: false
            }
        },
        methods: {
            formatPrettyDate(iso) {
                return moment(iso).format('MMMM Do, H:mm [JST]');
            },
            formatDate(iso) {
                return upperFirst(moment(iso).fromNow());
            },
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
