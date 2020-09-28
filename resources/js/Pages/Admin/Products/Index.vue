<template>
    <admin-layout header-title="Products">
        <flash/>

        <div class="py-5">
            <inertia-link :href="$route('admin.products.create')"
                          class="bg-transparent hover:bg-green-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                Create product
            </inertia-link>
        </div>

        <hr>

        <div class="categories" v-if="productsListLength">
            <div class="container py-10">
                <table class="table-auto mx-auto">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2">Controls</th>
                    </tr>
                    </thead>
                    <tbody>
                    <product-info
                            v-for="(product, index) in productsList"
                            :product="product"
                            :key="index"
                            :number="index"
                    />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="products"
                    @pagination-change-page="paginate"
                    class="flex justify-center"
            >
            </pagination>
        </div>

        <div class="empty-list mt-3 flex justify-center" v-else>
            <h1>No products right now...</h1>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import Flash from './../../../Assets/Flash'
    import ProductInfo from './Assets/ProductInfo'

    export default {
        name: "index",

        props: {
            products: {
                type: Object,
                required: true
            }
        },

        components: {
            AdminLayout,
            ProductInfo,
            Flash
        },

        data() {
            return {
                productsList: this.products.data
            }
        },

        methods: {
            paginate(page = 1) {
                this.$inertia.visit(`/admin/products?page=${page}`)
            }
        },

        computed: {
            productsListLength() {
                return this.productsList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>