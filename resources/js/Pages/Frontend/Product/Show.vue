<template>
    <app-layout>
        <div class="container mx-auto p-6 my-8">
            <!--Breadcrumbs-->
            <breadcrumbs :breadcrumbs="breadcrumbs"/>

            <!--Product information-->
            <section class="text-gray-700 body-font overflow-hidden bg-white">
                <div class="container mx-auto">
                    <div class="lg:w-4/5 mx-auto flex flex-wrap">
                        <div class="flex justify-between w-1/2 h-full max-h-96">
                            <product-images
                                :images="product.images"
                                @set-main-image="setMainImage"
                            />

                            <div
                                class="w-10/12 ml-2 max-h-full h-full"
                            >
                                <img :alt="product.image"
                                     class="w-full object-cover object-center rounded border border-gray-200"
                                     :src="product.main_image_path"
                                     ref="mainImage"
                                >
                            </div>
                        </div>

                        <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                            <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{product.name}}</h1>

                            <p class="leading-relaxed">
                                {{product.excerpt}}
                            </p>
                            <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-200 mb-5">
                                <p>
                                    <span class="text-gray-500 mt-3" v-for="category in product.categories">
                                        {{ category.name }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex">
                                <span class="title-font font-medium text-2xl text-gray-900">{{product.price}}</span>

                                <inertia-link
                                    :href="$route('add-to-cart', {product : product.slug})"
                                    preserve-scroll
                                    v-if="!inCart(product)"
                                    class="flex ml-auto px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </inertia-link>

                                <button
                                    class="rounded-full w-10 h-10 bg-gray-200 p-0 border-0 inline-flex items-center justify-center text-gray-500 ml-4">
                                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                         stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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
import ProductImages from "../../../Assets/Frontend/ProductImages";
import {Disqus} from 'vue-disqus'

export default {
    name: "show",

    props: ['product', 'similarProducts', 'breadcrumbs'],

    mixins: [InList],

    components: {
        AppLayout,
        Breadcrumbs,
        SimilarProducts,
        ProductImages,
        Disqus
    },

    methods : {
       setMainImage(val){
           return this.$refs.mainImage.src = val
       }
    },

    computed: {
        getUrl() {
            return window.location.href
        },

        imagesList() {
            return this.product.images.length > 0
        }
    }
}
</script>

<style scoped>

</style>
