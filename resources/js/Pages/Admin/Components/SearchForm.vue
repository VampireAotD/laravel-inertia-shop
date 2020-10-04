<template>
    <form class="mt-5 inline-flex" @submit.prevent="search">
        <div class="mr-3" v-if="issetProperty('name')">
            <input type="search" class="bg-purple-white shadow rounded border-0 p-3" placeholder="Search by name..."
                   v-model="searchForm.name">
        </div>

        <div class="mr-3" v-if="issetProperty('perPage')">
            <select name="per_page" @change="changePerPage" class="bg-purple-white shadow rounded border-0 p-3">
                <option value="10" selected>Choose categories per page</option>
                <option value="10" :selected="perPage === 10">10</option>
                <option value="25" :selected="perPage === 25">25</option>
                <option value="50" :selected="perPage === 50">50</option>
            </select>
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
            resetLink: {
                type: String
            },
            perPage: {
                type: Number
            }
        },

        methods: {
            issetProperty(property) {
                return this.searchForm.hasOwnProperty(property)
            },
            changePerPage(e){
                this.searchForm.perPage = e.target.value
            },
            search() {
                this.$inertia.visit(this.$route('admin.categories.search'), {
                    data: this.searchForm,
                    preserveScroll : true
                })
            }
        }
    }
</script>

<style scoped>

</style>