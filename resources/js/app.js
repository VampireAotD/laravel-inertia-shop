require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueIziToast from 'vue-izitoast';
import CKEditor from '@ckeditor/ckeditor5-vue'
import VueSlider from 'vue-slider-component'
import Authorize from './Backend/Authorize/Authorize'
import Viewer from 'v-viewer'
import VueSmoothScroll from 'vue2-smooth-scroll'

import 'izitoast/dist/css/iziToast.min.css';
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
import 'vue-slider-component/theme/default.css'
import 'viewerjs/dist/viewer.css'

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueIziToast);
Vue.use(CKEditor);
Vue.use(Authorize);
Vue.use(Viewer);
Vue.use(VueSmoothScroll)

const app = document.getElementById('app');

Vue.prototype.$route = (...args) => route(...args).url();
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('slick-slider', require('vue-slick-carousel'));
Vue.component('VueSlider', VueSlider);

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
