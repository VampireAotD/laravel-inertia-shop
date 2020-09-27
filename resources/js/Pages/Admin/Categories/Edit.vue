<template>
    <admin-layout :header-title="title">
        <div class="py-5">
            <inertia-link :href="$route('admin.categories.index')"
                          class="bg-transparent hover:bg-blue-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                Categories list
            </inertia-link>
        </div>

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
    import CategoryForm from './Assets/CategoryForm'

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
            CategoryForm
        },

        data() {
            return {
                form: this.$inertia.form({
                    '_method' : 'PUT',
                    name: this.category.name,
                    slug: this.category.slug
                })
            }
        },

        computed : {
            title(){
                return `Edit ${this.category.name}`
            }
        }
    }
</script>

<style scoped>

</style>