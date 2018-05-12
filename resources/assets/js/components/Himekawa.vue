<template>
    <md-list>
        <md-list-item md-expand v-for="app in availableApps" :key="app.id">
            <md-avatar>
                <img :src="app.image">
            </md-avatar>
            <div class="md-list-item-text">
                <span>{{ app.name }}</span>
                <span class="md-small-hide muted">{{ app.original_title }}</span>
                <span>{{ formatDate(app.available_apps[0].created_at) }}</span>
            </div>

            <md-list slot="md-expand">
                <md-list-item class="md-inset md-layout" v-for="(apk, index) in app.available_apps" :key="apk.version_code" @click="">
                    <div class="md-layout md-layout-item md-xsmall-size-85 md-size-70 md-alignment-center-left truncated">
                        <span :class="{ tags: true, latest: index === 0 }">v{{ apk.version_name }}</span>
                        <span class="md-layout md-alignment-center-left">{{ resolveApkFilename(app.package_name, apk.version_code) }}</span>
                    </div>
                    <div class="md-layout md-layout-item md-xsmall-size-15 md-size-30 md-alignment-center-right">
                        <md-button class="md-raised md-accent himekawa" :href="resolveApkUrl(app.package_name, apk.version_code)" :md-ripple="false">
                            <md-icon>file_download</md-icon>
                            <span class="md-xsmall-hide">Download</span>
                        </md-button>
                    </div>
                </md-list-item>
            </md-list>
        </md-list-item>
    </md-list>
</template>

<script>
    import moment from "moment";
    import {upperFirst} from "lodash-es";

    export default {
        props: ['availableApps'],
        methods: {
            formatDate(iso) {
                return upperFirst(moment(iso).fromNow());
            },
            resolveApkFilename(packageName, versionCode) {
                return `${packageName}.${versionCode}.apk`
            },
            resolveApkUrl(packageName, versionCode) {
                return location.href + `apk/${packageName}/${this.resolveApkFilename(packageName, versionCode)}`
            }
        }
    }
</script>
