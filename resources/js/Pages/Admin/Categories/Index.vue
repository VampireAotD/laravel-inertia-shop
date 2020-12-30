<template>
    <admin-layout header-title="Categories">
        <flash/>

        <inner-header
                :route="$route('admin.categories.create')"
                title="Create category"
                classes="bg-transparent hover:bg-green-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded mr-3"
        >
            <search-form
                    :search-form="searchForm"
                    search-link="admin.categories.search"
                    :per-page="perPage"
                    reset-link="admin.categories.index"
            />
        </inner-header>

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
                            :permissions="permissions"
                    />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="categories"
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
    import InnerHeader from './../Components/InnerHeader'
    import SearchForm from './../Components/SearchForm'
    import Flash from '../../../Assets/Backend/Flash'
    import CategoryInfo from './Assets/CategoryInfo'
    import Pagination from "../../../Assets/Common/Pagination";

    import CategoryPermissions from '../../../Mixins/Admin/Categories/CategoryPermissions'

    export default {
        name: "index",

        props: {
            categories: {
                type: Object,
                required: true
            },
            name: {
                type: String,
                default: ""
            },
            perPage: {
                type: Number,
                default: 10
            },
        },

        components: {
            AdminLayout,
            InnerHeader,
            SearchForm,
            CategoryInfo,
            Pagination,
            Flash
        },

        mixins: [
            CategoryPermissions
        ],

        data() {
            return {
                categoriesList: this.categories.data,

                searchForm: {
                    name: this.name,
                    perPage: this.perPage
                }
            }
        },

        methods: {
            paginate(page = 1) {
                if (this.$page.currentRouteName === 'admin.categories.search') {
                    return this.$inertia.replace(this.$route('admin.categories.search', {page}), {
                        data: this.searchForm,
                        preserveScroll: false,
                    })
                }

                this.$inertia.visit(this.$route('admin.categories.index', {page}))
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
