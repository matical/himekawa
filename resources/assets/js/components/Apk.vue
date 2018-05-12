<template>
    <md-list-item class="md-inset md-layout" @click="">
        <div class="md-layout md-layout-item md-xsmall-size-85 md-size-70 md-alignment-center-left truncated">
            <span :class="{ tags: true, latest: index === 0 }">v{{ apk.version_name }}</span>
            <span class="md-layout md-alignment-center-left">{{ resolveApkFilename(apk.version_code) }}</span>
        </div>
        <div class="md-layout md-layout-item md-xsmall-size-15 md-size-30 md-alignment-center-right">
            <md-button class="md-raised md-accent himekawa" :href="resolveApkUrl(apk.version_code)" :md-ripple="false">
                <md-icon>file_download</md-icon>
                <span class="md-xsmall-hide">Download</span>
            </md-button>
        </div>
    </md-list-item>
</template>

<script>
    export default {
        props: ['apk', 'index', 'packageName'],
        methods: {
            resolveApkFilename(versionCode) {
                return `${this.packageName}.${versionCode}.apk`
            },
            resolveApkUrl(versionCode) {
                return location.href + `apk/${this.packageName}/${this.resolveApkFilename(versionCode)}`
            }
        }
    }
</script>
