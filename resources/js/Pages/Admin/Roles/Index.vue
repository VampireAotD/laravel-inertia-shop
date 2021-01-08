<template>
    <admin-layout header-title="Roles">
        <flash/>

        <inner-header
                :route="$route('admin.roles.create')"
                title="Create role"
                classes="bg-transparent hover:bg-green-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded mr-3"
        >
        </inner-header>

        <hr>

        <div class="permissions" v-if="rolesListLength">
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
                    <role-info
                            v-for="(role, index) in rolesList"
                            :role="role"
                            :key="index"
                            :number="index"
                            :permissions="permissions"
                    />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="roles"
            >
            </pagination>
        </div>

        <div class="empty-list mt-3 flex justify-center" v-else>
            <h1>No roles right now...</h1>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import Flash from '../../../Assets/Backend/Flash'
    import RoleInfo from "./Assets/RoleInfo";
    import Pagination from "../../../Assets/Common/Pagination";

    import RolePermissions from "../../../Mixins/Admin/Roles/RolePermissions";

    export default {
        name: "index",

        props: {
            roles: {
                type: Object,
                required: true
            }
        },

        components: {
            RoleInfo,
            AdminLayout,
            InnerHeader,
            Pagination,
            Flash
        },

        mixins: [
            RolePermissions
        ],

        data() {
            return {
                rolesList: this.roles.data,
            }
        },

        methods: {
            paginate(page = 1) {
                this.$inertia.visit(this.$route('admin.roles.index', {page}))
            }
        },

        computed: {
            rolesListLength() {
                return this.rolesList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>
