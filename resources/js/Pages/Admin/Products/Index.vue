<template>
    <admin-layout header-title="Products">
        <flash/>

        <inner-header
                :route="$route('admin.products.create')"
                title="Create product"
                :permissions="permissions"
                classes="bg-transparent hover:bg-green-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded mr-3"
        >
            <search-form
                    :search-form="searchForm"
                    search-link="admin.products.search"
                    :per-page="perPage"
                    reset-link="admin.products.index"
            />
        </inner-header>

        <hr>

        <div class="orders" v-if="productsListLength">
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
                            :permissions="permissions"
                    />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="products"
                    :limit="3"
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
    import InnerHeader from './../Components/InnerHeader'
    import SearchForm from './../Components/SearchForm'
    import Flash from '../../../Assets/Backend/Flash'
    import ProductInfo from './Assets/ProductInfo'

    import ProductPermissions from '../../../Mixins/Admin/Products/ProductPermissions'

    export default {
        name: "index",

        props: {
            products: {
                type: Object,
                required: true
            },

            name: {
                type: String,
                default: ""
            },

            minimumPrice: {
                type: Number,
                default: 0
            },

            maximumPrice: {
                type: Number
            },

            minimumAmount: {
                type: Number,
                default: 0
            },

            maximumAmount: {
                type: Number
            },

            perPage: {
                type: Number,
                default: 10
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            SearchForm,
            ProductInfo,
            Flash
        },

        mixins : [
            ProductPermissions
        ],

        data() {
            return {
                productsList: this.products.data,
                searchForm: {
                    name: this.name,
                    price: [this.minimumPrice, this.maximumPrice],
                    amount: [this.minimumAmount, this.maximumAmount],
                    perPage: this.perPage,
                }
            }
        },

        methods: {
            paginate(page = 1) {
                if (this.$page.currentRouteName === 'admin.products.search') {
                    return this.$inertia.visit(this.$route('admin.products.search', {page}), {
                        data: this.searchForm,
                        preserveScroll: false,
                    })
                }

                this.$inertia.visit(this.$route('admin.products.index', {page}))
            }
        },

        computed: {
            productsListLength() {
                return this.productsList.length > 0
            }
        },
    }
</script>

<style scoped>

</style>