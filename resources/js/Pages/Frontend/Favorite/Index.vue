<template>
    <app-layout>
        <div class="container mx-auto">
            <h4 class="text-3xl text-gray-700 mb-5">Order Summary</h4>

            <hr>

            <form @submit.prevent="destroyFavoriteList" class="p-10 rounded-md shadow-md bg-white mt-2"
                  v-if="productsListExist">
                <item
                        :key="i"
                        v-for="(item, i) in products"
                        :item="item"
                />

                <button>
                    Destroy
                </button>
            </form>

            <p v-else>Empty</p>

        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '../../../Layouts/AppLayout'
    import Item from './Item'

    export default {
        name: "favorite",

        components: {
            AppLayout,
            Item
        },

        props: ['products'],

        data() {
            return {
                destroyForm: this.$inertia.form({
                    '_method': 'DELETE'
                })
            }
        },

        methods: {
            destroyFavoriteList() {
                this.destroyForm.delete(this.$route('destroy-favorite-list'), {
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