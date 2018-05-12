<template>
    <md-app-drawer :md-active.sync="menuVisible">
        <md-toolbar class="md-transparent" md-elevation="0">
            <span>Navigation</span>

            <div class="md-toolbar-section-end">
                <md-button class="md-icon-button md-dense" @click="toggleMenu">
                    <md-icon>keyboard_arrow_left</md-icon>
                </md-button>
            </div>
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
