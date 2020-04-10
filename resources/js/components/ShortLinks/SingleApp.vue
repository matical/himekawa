<template>
    <md-list-item class="md-layout-item md-xlarge-size-20 md-large-size-33 md-small-size-50 md-xsmall-size-100">
        <md-avatar><img :src="watchedApp.image"/></md-avatar>
        <div class="md-list-item-text">
            <p>{{ watchedApp.name }}</p>
            <p
                >v{{ watchedApp.available_apps[0].version_name }}
                <span :class="{ recent: isRecent(watchedApp.available_apps[0].created_at) }">
                    ({{ diffDate(watchedApp.updated_at) }})
                </span>
            </p>
            <p
                ><a :href="linkToShortRedirector(watchedApp.slug)">
                    {{ linkToShortRedirector(watchedApp.slug) }}
                </a></p
            >
        </div>
    </md-list-item>
</template>

<script>
export default {
    props: ['watchedApp'],

    methods: {
        linkToShortRedirector(slug) {
            return `${location.origin}/${slug}`;
        },
    },
};
</script>
