<template>
    <admin-layout :header-title="title">

        <inner-header
                :route="$route('admin.roles.index')"
                title="Roles list"
        />

        <hr>

        <div class="container bg-white rounded shadow mx-auto mt-2">
            <header class="p-4">
                <h3 class="text-lg font-bold">{{ role.name }}</h3>
                <p class="text-sm text-gray-600">Created {{ role.created_date }}</p>
                <p class="text-sm text-gray-600">Last updated {{ role.updated_date }}</p>
                <control-buttons
                        :routes="routes"
                        :permissions="permissions"
                        class="mt-2"
                />
            </header>

            <section v-if="permissionsListLength" class="p-4">
                <p class="text-sm text-gray-600">Permissions for this role :</p>

                <ul class=" list-reset flex flex-col">
                    <li
                            class="relative -mb-px block border p-4 border-grey"
                            v-for="(permission, index) in permissionsList"
                            :key="index"
                    >
                        <inertia-link :href="$route('admin.permissions.show', {permission : permission.id})">
                            {{ permission.name }}
                        </inertia-link>
                    </li>
                </ul>
            </section>

        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import ControlButtons from '../../../Assets/Backend/ControlButtons'

    import RolePermissions from "../../../Mixins/Admin/Roles/RolePermissions";
    import DefaultCrudRoutes from "../../../Mixins/Admin/Roles/DefaultCrudRoutes";

    export default {
        name: "show",

        props: {
            role: {
                type: Object,
                required: true
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            ControlButtons
        },

        mixins: [
            RolePermissions,
            DefaultCrudRoutes
        ],

        data() {
            return {
                permissionsList: this.role.permissions
            }
        },

        computed: {
            title() {
                return `${this.role.name} details`
            },

            permissionsListLength() {
                return this.permissionsList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>
