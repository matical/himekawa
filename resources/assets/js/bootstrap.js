try {
    window._ = require('lodash');
    window.$ = window.jQuery = require('jquery');

} catch (e) {}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

window.Vue = require('vue');
window.VueMaterial = require('vue-material');
