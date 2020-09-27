<template>
    <admin-layout header-title="Categories">
        <flash/>

        <div class="py-5">
            <inertia-link :href="$route('admin.categories.create')"
                          class="bg-transparent hover:bg-green-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                Create category
            </inertia-link>
        </div>

        <hr>

        <div class="categories" v-if="categoriesListLength">
            <div class="container py-10">
                <table class="table-auto mx-auto">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2">Controls</th>
                    </tr>
                    </thead>
                    <tbody>
                        <category-info
                                v-for="(category, index) in categoriesList"
                                :category="category"
                                :key="index"
                                :number="index"
                        />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="categories"
                    @pagination-change-page="paginate"
                    class="flex justify-center"
            >
            </pagination>
        </div>

        <div class="empty-list mt-3 flex justify-center" v-else>
            <h1>No categories right now...</h1>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import Flash from './../../../Assets/Flash'
    import CategoryInfo from './Assets/CategoryInfo'

    export default {
        name: "index",

        props: {
            categories: {
                type: Object,
                required: true
            }
        },

        components: {
            AdminLayout,
            CategoryInfo,
            Flash
        },

        data() {
            return {
                categoriesList: this.categories.data
            }
        },

        methods: {
            paginate(page = 1) {
                this.$inertia.visit(`/admin/categories?page=${page}`)
            }
        },

        computed: {
            categoriesListLength() {
                return this.categoriesList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>