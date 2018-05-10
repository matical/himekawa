<template>
    <md-app-drawer md-permanent="full">
        <md-toolbar class="md-transparent" md-elevation="0">
            Navigation
        </md-toolbar>

        <md-list>
            <md-list-item>
                <md-icon>move_to_inbox</md-icon>
                <span class="md-list-item-text">Inbox</span>
            </md-list-item>

            <md-list-item>
                <md-icon>send</md-icon>
                <span class="md-list-item-text">Sent Mail</span>
            </md-list-item>

            <md-list-item>
                <md-icon>delete</md-icon>
                <span class="md-list-item-text">Trash</span>
            </md-list-item>

            <md-list-item>
                <md-icon>error</md-icon>
                <span class="md-list-item-text">Spam</span>
            </md-list-item>
        </md-list>
    </md-app-drawer>
</template>
