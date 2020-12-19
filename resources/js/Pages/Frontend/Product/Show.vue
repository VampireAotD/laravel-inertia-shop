<template>
    <app-layout>
        <div class="container mx-auto p-6 my-8">
            <!--Breadcrumbs-->
            <breadcrumbs :breadcrumbs="breadcrumbs"/>

            <!--Product information-->
            <div class="md:flex md:items-center">
                <div class="w-full h-64 md:w-1/2 lg:h-96">
                    <img class="h-full w-full rounded-md object-cover max-w-lg mx-auto"
                         :src="product.main_image_path"
                         :alt="product.name">
                </div>
                <div class="w-full max-w-lg mx-auto mt-5 md:ml-8 md:mt-0 md:w-1/2">
                    <h3 class="text-gray-700 uppercase text-lg">{{ product.name }}</h3>
                    <span class="text-gray-500 mt-3">{{ product.price }}</span>
                    <p>
                        <span class="text-gray-500 mt-3" v-for="category in product.categories">
                            {{category.name}}
                        </span>
                    </p>

                    <hr class="my-3">

                    <div class="flex items-center mt-6">
                        <inertia-link
                            :href="$route('add-to-cart', {product : product.slug})"
                            preserve-scroll
                            v-if="!inCart(product)"
                            class="px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500"
                        >
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                 stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </inertia-link>
                    </div>
                    <div class="mt-2">
                        <span class="text-gray-700 text-sm">Description</span>
                        <p>{{ product.excerpt }}</p>
                    </div>
                </div>
            </div>

            <!--Comments-->
            <div class="mt-20 flex justify-center">
                <div class='w-full'>
                    <Disqus
                        shortname='https-laravel-inertia-shop-com-ua'
                        :identifier="product.slug"
                        :url="getUrl"
                    />
                </div>
            </div>

            <!--Similar products list-->
            <similar-products :products="similarProducts"></similar-products>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../../Layouts/AppLayout'

import Breadcrumbs from "../../../Assets/Frontend/Breadcrumbs";
import InList from '../../../Mixins/Frontend/InList'
import SimilarProducts from "../../../Assets/Frontend/SimilarProducts";
import {Disqus} from 'vue-disqus'

export default {
    name: "show",

    props: ['product', 'similarProducts', 'breadcrumbs'],

    mixins: [InList],

    components: {
        AppLayout,
        Breadcrumbs,
        SimilarProducts,
        Disqus
    },

    computed: {
        getUrl() {
            return window.location.href
        }
    }
}
</script>

<style scoped>

</style>
