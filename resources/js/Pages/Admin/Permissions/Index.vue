<template>
    <admin-layout header-title="Permissions">
        <flash/>

        <inner-header
                :route="$route('admin.permissions.create')"
                title="Create permission"
                classes="bg-transparent hover:bg-green-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded mr-3"
        >
        </inner-header>

        <hr>

        <div class="permissions" v-if="permissionsListLength">
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
                    <permission-info
                            v-for="(permission, index) in permissionsList"
                            :permission="permission"
                            :key="index"
                            :number="index"
                            :permissions="permissions"
                    />
                    </tbody>
                </table>
            </div>

            <pagination
                    :data="allPermissions"
            >
            </pagination>
        </div>

        <div class="empty-list mt-3 flex justify-center" v-else>
            <h1>No permissions right now...</h1>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import Flash from '../../../Assets/Backend/Flash'
    import PermissionInfo from "./Assets/PermissionInfo";
    import Pagination from "../../../Assets/Common/Pagination";

    import PermissionPermissions from "../../../Mixins/Admin/Permissions/PermissionPermissions";

    export default {
        name: "index",

        props: {
            allPermissions: {
                type: Object,
                required: true
            }
        },

        components: {
            PermissionInfo,
            AdminLayout,
            InnerHeader,
            Pagination,
            Flash
        },

        mixins: [
            PermissionPermissions
        ],

        data() {
            return {
                permissionsList: this.allPermissions.data,
            }
        },

        methods: {
            paginate(page = 1) {
                this.$inertia.visit(this.$route('admin.permissions.index', {page}))
            }
        },

        computed: {
            permissionsListLength() {
                return this.permissionsList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>
