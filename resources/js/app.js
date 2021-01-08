require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueIziToast from 'vue-izitoast';
import CKEditor from '@ckeditor/ckeditor5-vue'
import VueSlider from 'vue-slider-component'
import Authorize from './Services/Common/Authorize/Authorize'
import Viewer from 'v-viewer'
import VueSmoothScroll from 'vue2-smooth-scroll'
import Socket from './Services/Common/Socket/Connect'
import VueAwesomeSwiper from 'vue-awesome-swiper'
import translate from "./Mixins/Common/translate";

import 'izitoast/dist/css/iziToast.min.css';
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
import 'vue-slider-component/theme/default.css'
import 'viewerjs/dist/viewer.css'
import 'swiper/swiper-bundle.css'

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueIziToast);
Vue.use(CKEditor);
Vue.use(Authorize);
Vue.use(Viewer);
Vue.use(VueSmoothScroll)
Vue.use(Socket)
Vue.use(VueAwesomeSwiper)

const app = document.getElementById('app');

Vue.prototype.$route = (...args) => route(...args).url();
Vue.component('slick-slider', require('vue-slick-carousel'));
Vue.component('VueSlider', VueSlider);

Vue.mixin(translate)

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);

