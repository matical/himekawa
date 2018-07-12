import Vue from 'vue';
import VueMaterial from 'vue-material';

import moment from 'moment';
import {upperFirst} from "lodash-es";

import yuki from "./components/ShortLinks/Yuki";
import himekawa from "./components/Index/Himekawa";

Vue.use(VueMaterial);

Vue.component('himekawa', himekawa);
Vue.component('yuki', yuki);

Vue.mixin({
    methods: {
        formatPrettyDate(iso) {
            return moment(iso).format('MMMM Do, H:mm [JST]');
        },
        diffDate(iso) {
            return upperFirst(moment(iso).fromNow());
        },
        isRecent(iso) {
            return this.diffInDays(moment(iso)) < 3;
        },
        diffInDays(from) {
            return moment().diff(from, 'days');
        }
    }
});

new Vue({
    el: '#app',
    data: {
        menuVisible: false
    },
    methods: {
        toggleMenu() {
            this.menuVisible = ! this.menuVisible
        }
    }
});
