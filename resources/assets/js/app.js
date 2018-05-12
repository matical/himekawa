import Vue from 'vue';
import VueMaterial from 'vue-material';

Vue.use(VueMaterial);
Vue.component('himekawa', require('./components/Himekawa'));

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
