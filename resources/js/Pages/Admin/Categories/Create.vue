<template>
    <admin-layout header-title="Create new category">

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
        name: "create",

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
                    name: this.category.name,
                    slug: this.category.slug
                })
            }
        }
    }
</script>

<style scoped>

</style>
