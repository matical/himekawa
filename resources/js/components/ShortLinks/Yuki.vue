<template>
    <div class="s-container">
        <h2 class="md-headline">Short Links</h2>
        <p>Links here will always redirect you to the latest available APK version.</p>
        <md-list class="md-double-line">
            <div class="md-layout md-layout-nowrap md-gutter">
                <md-list-item
                    v-for="watched in availableApps"
                    :key="watched.id"
                    class="md-layout-item md-xlarge-size-20 md-large-size-33 md-small-size-50 md-xsmall-size-100"
                >
                    <md-avatar><img :src="watched.image"/></md-avatar>
                    <div class="md-list-item-text">
                        <span>{{ watched.name }}</span>
                        <span
                            >v{{ watched.available_apps[0].version_name }}
                            <span :class="{ recent: isRecent(watched.available_apps[0].created_at) }">
                                ({{ diffDate(watched.updated_at) }})
                            </span>
                        </span>
                        <span
                            ><a :href="linkToShortRedirector(watched.slug)">
                                {{ linkToShortRedirector(watched.slug) }}
                            </a></span
                        >
                    </div>
                </md-list-item>
            </div>
        </md-list>
    </div>
</template>

<script>
export default {
    props: ['availableApps'],
    data() {
        return {
            hovered: false,
        };
    },
    methods: {
        linkToShortRedirector(slug) {
            return `${location.origin}/${slug}`;
        },
    },
};
</script>
