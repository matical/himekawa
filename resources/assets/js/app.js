/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueMaterial);

Vue.material.registerTheme('default', {
    primary: {
        color: 'grey',
        hue: 800,
        textColor: 'white'
    },
    accent: 'orange',
    background: {
        color: 'grey',
        hue: 900,
        textColor: 'white'
    }
});

const app = new Vue({
    el: '#app',
    methods: {
        toggleSidenav() {
            this.$refs.leftSidenav.toggle();
        },
    }
});
