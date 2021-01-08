<template>
    <admin-layout :header-title="title">

        <loader
            v-if="form.processing"
        />

        <inner-header
            :route="$route('admin.roles.index')"
            title="Roles list"
        />

        <hr>

        <div class="container mx-auto mt-5">
            <role-form
                :form="form"
                mode="edit"
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
    name: "edit",

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
                '_method': 'PUT',
                id: this.role.id,
                name: this.role.name,
                permissionsList: this.permissionsList,
                permissions: this.role.permissions.map(permission => permission.id),
            })
        }
    },

    computed: {
        title() {
            return `Edit ${this.role.name}`
        }
    }
}
</script>

<style scoped>

</style>
