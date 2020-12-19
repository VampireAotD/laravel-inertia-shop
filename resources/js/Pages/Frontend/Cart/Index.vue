<template>
    <app-layout>
        <div class="container mx-auto">
            <breadcrumbs :breadcrumbs="breadcrumbs"/>

            <div class="container mx-auto p-3">
                <h4 class="text-3xl text-gray-700 mb-5">Cart</h4>

                <hr>

                <form @submit.prevent class="p-10 rounded-md shadow-md bg-white mt-2"
                      v-if="productsListExist">
                    <item
                        :key="i"
                        v-for="(item, i) in products"
                        :item="item"
                    />

                    <button
                        @click="order"
                        class="h-10 px-5 m-2 text-blue-100 transition-colors duration-150 bg-blue-600 rounded-lg focus:shadow-outline hover:bg-blue-700"
                    >
                        Order
                    </button>

                    <button
                        @click="destroyCart"
                        class="h-10 px-5 m-2 text-red-100 transition-colors duration-150 bg-red-700 rounded-lg focus:shadow-outline hover:bg-red-800"
                        title="Delete all products from your cart"
                    >
                        Clear
                    </button>
                </form>

                <p
                    class="text-center py-3"
                    v-else
                >
                    Your cart is empty
                </p>
            </div>

        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../../Layouts/AppLayout'
import Breadcrumbs from "../../../Assets/Frontend/Breadcrumbs";
import Item from "./Item";

    export default {
        name: "cart",

        components : {
            AppLayout,
            Breadcrumbs,
            Item
        },

        props : ['products', 'breadcrumbs'],

        methods: {
            destroyCart() {
                this.$inertia.delete(this.$route('destroy-cart'), {
                    preserveScroll: true
                })
            },

            order(){
                this.$inertia.visit(this.$route('order'), {
                    preserveScroll: true
                })
            }
        },

        computed: {
            productsListExist() {
                return this.products.length > 0
            }
        }
    }
</script>

<style scoped>

</style>
