import Vue from 'vue';
import VueMaterial from 'vue-material';

Vue.use(VueMaterial);

Vue.component('himekawa', require('./components/Index/Himekawa'));
Vue.component('app-item', require('./components/Index/AvailableApp'));
Vue.component('apk', require('./components/Index/Apk'));

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
