<template>
    <admin-layout :header-title="title">

        <loader
                v-if="form.processing"
        />

        <inner-header
                :route="$route('admin.categories.index')"
                title="Categories list"
        />

        <hr>

        <div class="container mx-auto mt-5">
            <category-form
                    :form="form"
                    mode="edit"
            />
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import CategoryForm from './Assets/CategoryForm'
    import Loader from '../../../Assets/Backend/Loader'

    export default {
        name: "edit",

        props: {
            category: {
                type: Object,
                required: true
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            CategoryForm,
            Loader
        },

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.category.name,
                    slug: this.category.slug
                })
            }
        },

        computed: {
            title() {
                return `Edit ${this.category.name}`
            }
        }
    }
</script>

<style scoped>

</style>
