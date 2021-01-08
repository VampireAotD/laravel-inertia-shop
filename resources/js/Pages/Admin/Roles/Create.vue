<template>
    <admin-layout header-title="Create new role">

        <loader
            v-if="form.processing"
        />

        <inner-header
            :route="$route('admin.roles.index')"
            title="Role list"
        />

        <hr>

        <div class="container mx-auto mt-5">
            <role-form
                :form="form"
            />
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from './../../../Layouts/AdminLayout'
import InnerHeader from './../Components/InnerHeader'
import RoleForm from "./Assets/RoleForm";
import Loader from '../../../Assets/Backend/Loader'

export default {
    name: "create",

    props: {
        role: {
            type: Object,
            required: true
        },

        permissionsList: {
            type: Array,
            required: true
        }
    },

    components: {
        AdminLayout,
        InnerHeader,
        RoleForm,
        Loader
    },

    data() {
        return {
            form: this.$inertia.form({
                name: this.role.name,
                permissions : [],
                permissionsList: this.permissionsList
            })
        }
    }
}
</script>

<style scoped>

</style>
