import Vue from 'vue';
import VueMaterial from 'vue-material';

import yuki from './components/ShortLinks/Yuki';
import himekawa from './components/Index/Himekawa';
import mixins from './mixins';

Vue.use(VueMaterial);

Vue.mixin({methods: mixins});

new Vue({
    el: '#app',
    data: {
        menuVisible: false
    },
    components: {
        himekawa,
        yuki
    },
    methods: {
        toggleMenu() {
            this.menuVisible = ! this.menuVisible
        }
    }
});
