<template>
    <app-layout>
        <div class="container mx-auto p-6 my-8">
            <div class="md:flex md:items-center">
                <div class="w-full h-64 md:w-1/2 lg:h-96">
                    <img class="h-full w-full rounded-md object-cover max-w-lg mx-auto"
                         :src="product.main_image_path"
                         :alt="product.name">
                </div>
                <div class="w-full max-w-lg mx-auto mt-5 md:ml-8 md:mt-0 md:w-1/2">
                    <h3 class="text-gray-700 uppercase text-lg">{{product.name}}</h3>
                    <span class="text-gray-500 mt-3">{{product.price}}</span>
                    <hr class="my-3">
                    <div class="flex items-center mt-6">
                        <inertia-link :href="$route('add-to-cart', {product : product.slug})" preserve-scroll v-if="!inCart(product)"
                                      class="px-8 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                 stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </inertia-link>
                    </div>
                    <div class="mt-2">
                        <span class="text-gray-700 text-sm">Description</span>
                        <p>{{product.excerpt}}</p>
                    </div>
                </div>
            </div>
            <div class="mt-16">
                <h3 class="text-gray-600 text-2xl font-medium">Similar Products</h3>
                <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
                    <div class="w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden"
                         v-for="(similarProduct, index) in similarProducts" :key="index">
                        <div class="flex items-end justify-end h-56 w-full bg-cover"
                             :style="bgImage(similarProduct)">
                            <inertia-link :href="$route('add-to-cart', {product : product.slug})" preserve-scroll v-if="!inCart(product)"
                                          class="p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500">
                                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </inertia-link>
                        </div>
                        <div class="px-5 py-3">
                            <h3 class="text-gray-700 uppercase">{{similarProduct.name}}</h3>
                            <span class="text-gray-500 mt-2">{{similarProduct.price}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '../../../Layouts/AppLayout'

    import InCart from '../../../Mixins/Frontend/InCart'

    export default {
        name: "show",

        props: ['product', 'similarProducts'],

        mixins : [InCart],

        components: {
            AppLayout
        },

        methods: {
            bgImage({main_image_path}) {
                return `background-image: url(${main_image_path})`
            },
        }
    }
</script>

<style scoped>

</style>