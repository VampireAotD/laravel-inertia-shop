require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueIziToast from 'vue-izitoast';

import 'izitoast/dist/css/iziToast.min.css';

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueIziToast);

const app = document.getElementById('app');
Vue.prototype.$route = (...args) => route(...args).url();
Vue.component('pagination', require('laravel-vue-pagination'));

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
