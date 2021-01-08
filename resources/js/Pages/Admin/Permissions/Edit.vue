<template>
    <admin-layout :header-title="title">

        <loader
                v-if="form.processing"
        />

        <inner-header
                :route="$route('admin.permissions.index')"
                title="Permissions list"
        />

        <hr>

        <div class="container mx-auto mt-5">
            <permission-form
                    :form="form"
                    mode="edit"
            />
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import PermissionForm from "./Assets/PermissionForm";
    import Loader from '../../../Assets/Backend/Loader'

    export default {
        name: "edit",

        props: {
            permission: {
                type: Object,
                required: true
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            PermissionForm,
            Loader
        },

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    id : this.permission.id,
                    name: this.permission.name,
                })
            }
        },

        computed: {
            title() {
                return `Edit ${this.permission.name}`
            }
        }
    }
</script>

<style scoped>

</style>
