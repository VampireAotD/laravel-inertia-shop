<template>
    <admin-layout header-title="Create new permission">

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
        name: "create",

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
                    name: this.permission.name,
                })
            }
        }
    }
</script>

<style scoped>

</style>
