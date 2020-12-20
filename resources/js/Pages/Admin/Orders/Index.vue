<template>
    <admin-layout header-title="Orders">
        <flash/>

        <inner-header
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

        <div class="orders" v-if="ordersList">
            <div class="container py-10">
                <table class="table-auto mx-auto">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Ordered products</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Ordered</th>
                        <th class="px-4 py-2">Controls</th>
                    </tr>
                    </thead>
                    <tbody>
                        <order-info
                            v-for="(order, index) in ordersList"
                            :order="order"
                            :number="index"
                            :key="order.id"
                        />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="orders"
                    :limit="3"
                    @pagination-change-page="paginate"
                    class="flex justify-center"
            >
            </pagination>
        </div>

        <div class="empty-list mt-3 flex justify-center" v-else>
            <h1>No orders right now...</h1>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import SearchForm from './../Components/SearchForm'
    import Flash from '../../../Assets/Backend/Flash'

    import OrderInfo from "./Assets/OrderInfo";

    export default {
        name: "index",

        props: {
            orders: {
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
            OrderInfo,
            Flash
        },

        mixins : [

        ],

        data() {
            return {
                ordersList: this.orders.data,
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
                if (this.$page.currentRouteName === 'admin.orders.search') {
                    return this.$inertia.visit(this.$route('admin.orders.search', {page}), {
                        data: this.searchForm,
                        preserveScroll: false,
                    })
                }

                this.$inertia.visit(this.$route('admin.orders.index', {page}))
            }
        },

        computed: {
            ordersListLength() {
                return this.ordersList.length > 0
            }
        },
    }
</script>

<style scoped>

</style>
