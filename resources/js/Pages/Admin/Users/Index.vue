<template>
    <admin-layout header-title="Users">
        <flash/>

        <inner-header>
            <search-form
                    :search-form="searchForm"
                    search-link="admin.users.search"
                    :per-page="perPage"
                    reset-link="admin.users.index"
            />
        </inner-header>

        <hr>

        <div class="users" v-if="usersListLength">
            <div class="container py-10">
                <table class="table-auto mx-auto">
                    <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Total orders</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2">Controls</th>
                    </tr>
                    </thead>
                    <tbody>
                    <user-info
                            v-for="(user, index) in usersList"
                            :user="user"
                            :key="index"
                            :number="index"
                            :permissions="permissions"
                    />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="users"
                    :limit="3"
                    @pagination-change-page="paginate"
                    class="flex justify-center"
            >
            </pagination>
        </div>

        <div class="empty-list mt-3 flex justify-center" v-else>
            <h1>No users right now...</h1>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import SearchForm from './../Components/SearchForm'
    import Flash from '../../../Assets/Backend/Flash'
    import UserInfo from './Assets/UserInfo'

    import UserPermissions from '../../../Mixins/Admin/Users/UserPermissions'

    export default {
        name: "index",

        props: {
            users: {
                type: Object,
                required: true
            },
            name: {
                type: String,
                default: ""
            },
            email: {
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
            UserInfo,
            Flash
        },

        mixins: [
            UserPermissions
        ],

        data() {
            return {
                usersList: this.users.data,

                searchForm: {
                    name: this.name,
                    email: this.email,
                    perPage: this.perPage
                }
            }
        },

        methods: {
            paginate(page = 1) {
                if (this.$page.currentRouteName === 'admin.users.search') {
                    return this.$inertia.replace(this.$route('admin.users.search', {page}), {
                        data: this.searchForm,
                        preserveScroll: false,
                    })
                }

                this.$inertia.visit(this.$route('admin.users.index', {page}))
            }
        },

        computed: {
            usersListLength() {
                return this.usersList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>