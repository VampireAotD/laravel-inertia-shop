<template>
    <form class="mt-5 inline-flex" @submit.prevent="search">
        <div class="mr-3" v-if="issetProperty('name')">
            <input type="search" class="bg-purple-white shadow rounded border-0 p-3" placeholder="Search by name..."
                   v-model="searchForm.name">
        </div>

        <div class="mr-3" v-if="issetProperty('email')">
            <input type="search" class="bg-purple-white shadow rounded border-0 p-3" placeholder="Search by email..."
                   v-model="searchForm.email">
        </div>

        <div class="mr-3" v-if="issetProperty('perPage')">
            <select name="per_page" @change="changePerPage" class="bg-purple-white shadow rounded border-0 p-3">
                <option value="10" selected>{{ dropDownTitle }}</option>
                <option value="10" :selected="perPage === 10">10</option>
                <option value="25" :selected="perPage === 25">25</option>
                <option value="50" :selected="perPage === 50">50</option>
            </select>
        </div>

        <div class="mr-5 w-48 flex flex-col items-center" v-if="issetProperty('price')">
            <p class="text-gray-300">Price filter</p>
            <vue-slider
                    v-model="searchForm.price"
                    v-bind="priceOptions"
            ></vue-slider>
        </div>

        <div class="mr-5 w-48 flex flex-col items-center" v-if="issetProperty('amount')">
            <p class="text-gray-300">Amount filter</p>
            <vue-slider
                    v-model="searchForm.amount"
                    v-bind="amountOptions"
            ></vue-slider>
        </div>

        <button type="submit"
                class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded mr-3">
            Filter
        </button>
        <inertia-link :href="$route(resetLink)"
                      class="bg-transparent hover:bg-red-500 text-black font-semibold hover:text-white py-1 px-4 leading-9 border border-red-500 hover:border-transparent rounded mr-3"
                      preserve-scroll
        >
            Reset
        </inertia-link>
    </form>
</template>

<script>
    export default {
        name: "search-form",

        props: {
            searchForm: {
                type: Object,
                required: true
            },

            searchLink: {
                type: String,
                required: true
            },

            dropDownTitle: {
                type: String,
                default: 'Per page'
            },

            perPage: {
                type: Number
            },

            resetLink: {
                type: String,
                required: true
            },
        },

        data() {
            return {
                orderOptions: {
                    width: '100%',
                    height: 6,
                    min: 0,
                    max: this.searchForm.orders ? this.searchForm.orders[1] : null,
                },

                priceOptions: {
                    width: '100%',
                    height: 6,
                    min: 0,
                    max: this.searchForm.price ? this.searchForm.price[1] : null,
                },

                amountOptions: {
                    width: '100%',
                    height: 6,
                    min: 0,
                    max: this.searchForm.price ? this.searchForm.amount[1] : null,
                }
            }
        },

        methods: {
            issetProperty(property) {
                return this.searchForm.hasOwnProperty(property)
            },

            changePerPage(e) {
                this.searchForm.perPage = e.target.value
            },

            search() {
                this.$inertia.visit(this.$route(this.searchLink), {
                    data: this.searchForm,
                    preserveScroll: true
                })
            }
        },
    }
</script>

<style scoped>

</style>