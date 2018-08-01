<template>
    <md-list-item class="md-inset md-layout" title="Click to show hash">
        <div class="md-layout md-layout-item md-xsmall-size-85 md-size-70 md-alignment-center-left truncated">
            <span :class="{ tags: true, latest: index === 0 }">v{{ apk.version_name }}</span>
            <span @click="toggleHashVisibility" class="md-layout md-alignment-center-left">{{ title }}</span>
        </div>
        <div class="md-layout md-layout-item md-xsmall-size-15 md-size-30 md-alignment-center-right">
            <md-button :class="{
                    'md-raised': true,
                    'md-accent': true,
                    'download__old': index !== 0,
                    'himekawa': index === 0
                }"
                       :href="url" :md-ripple="false">
                <md-icon>file_download</md-icon>
                <span class="md-xsmall-hide">{{ apk.size | humanBytes }}</span>
            </md-button>
        </div>
    </md-list-item>
</template>

<script>
export default {
    props: ['apk', 'index', 'packageName'],

    data() {
        return {
            state: { hash: false },
        };
    },

    computed: {
        title() {
            return this.state.hash ? this.apk.hash : this.filename;
        },
        filename() {
            return `${this.packageName}.${this.apk.version_code}.apk`;
        },
        url() {
            return location.href + `apks/${this.packageName}/${this.filename}`;
        },
    },

    methods: {
        toggleHashVisibility() {
            this.state.hash = !this.state.hash;
        },
    },
};
</script>

<style lang="scss" scoped>
.download__old {
    background-color: #444444 !important;
}
</style>
