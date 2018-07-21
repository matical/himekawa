<template>
    <md-list-item class="md-inset md-layout" title="Click to show hash" @click="toggleHashVisibility">
        <div class="md-layout md-layout-item md-xsmall-size-85 md-size-70 md-alignment-center-left truncated">
            <span :class="{ tags: true, latest: index === 0 }">v{{ apk.version_name }}</span>
            <span class="md-layout md-alignment-center-left">{{ title }}</span>
        </div>
        <div class="md-layout md-layout-item md-xsmall-size-15 md-size-30 md-alignment-center-right">
            <md-button :class="{
                    'md-raised': true,
                    'md-accent': true,
                    'download__old': index !== 0,
                    'himekawa': index === 0
                }"
                       :href="resolveApkUrl(apk.version_code)" :md-ripple="false">
                <md-icon>file_download</md-icon>
                <span class="md-xsmall-hide">{{ humanBytes(apk.size) }}</span>
            </md-button>
        </div>
    </md-list-item>
</template>

<script>
    export default {
        props: ['apk', 'index', 'packageName'],

        data() {
            return {
                state: {hash: false}
            }
        },

        computed: {
            title() {
                if (this.state.hash) {
                    return this.apk.hash;
                }

                return this.resolveApkFilename(this.apk.version_code);
            }
        },

        methods: {
            toggleHashVisibility() {
                this.state.hash = ! this.state.hash;
            },
            resolveApkFilename(versionCode) {
                return `${this.packageName}.${versionCode}.apk`
            },
            resolveApkUrl(versionCode) {
                return location.href + `apks/${this.packageName}/${this.resolveApkFilename(versionCode)}`
            },
            humanBytes(sizeInBytes) {
                const units = ["B", "KB", "MB", "GB", "TB", "PB"];

                if (sizeInBytes === 0) {
                    return "0 " + units[1];
                }

                for (var i = 0; sizeInBytes > 1024; i ++) {
                    sizeInBytes /= 1024;
                }

                return sizeInBytes.toFixed(2) + " " + units[i];
            }
        }
    }
</script>

<style lang="scss" scoped>
    .download__old {
        background-color: #444444 !important;
    }
</style>
