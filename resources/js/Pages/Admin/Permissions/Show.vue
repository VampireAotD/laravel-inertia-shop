<template>
    <admin-layout :header-title="title">

        <inner-header
            :route="$route('admin.permissions.index')"
            title="Permissions list"
        />

        <hr>

        <div class="container bg-white rounded shadow mx-auto mt-2">
            <header class="p-4">
                <h3 class="text-lg font-bold">{{ permission.name }}</h3>
                <p class="text-sm text-gray-600">Created {{ permission.created_date }}</p>
                <p class="text-sm text-gray-600">Last updated {{ permission.updated_date }}</p>
                <control-buttons
                    :routes="routes"
                    :permissions="permissions"
                    class="mt-2"
                />
            </header>

            <section v-if="usersWithPermissionLength" class="p-4">
                <p class="text-sm text-gray-600">Users with this permissions :</p>

                <ul class=" list-reset flex flex-col">
                    <li
                        class="relative -mb-px block border p-4 border-grey"
                        v-for="(user, index) in usersWithPermission"
                        :key="index"
                    >
                        <inertia-link :href="$route('admin.users.show', {user : user.id})">
                            {{ user.name }}
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

import PermissionPermissions from "../../../Mixins/Admin/Permissions/PermissionPermissions";
import DefaultCrudRoutes from "../../../Mixins/Admin/Permissions/DefaultCrudRoutes";

export default {
    name: "show",

    props: {
        permission: {
            type: Object,
            required: true
        },

        usersWithPermission : {
            type : Array
        }
    },

    components: {
        AdminLayout,
        InnerHeader,
        ControlButtons
    },

    mixins: [
        PermissionPermissions,
        DefaultCrudRoutes
    ],

    computed: {
        title() {
            return `${this.permission.name} details`
        },

        usersWithPermissionLength(){
            return this.usersWithPermission.length > 0
        }
    }
}
</script>

<style scoped>

</style>
