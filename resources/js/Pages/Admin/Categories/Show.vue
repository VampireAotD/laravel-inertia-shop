<template>
    <admin-layout :header-title="title">

        <inner-header
                :route="$route('admin.categories.index')"
                title="Categories list"
        />

        <hr>

        <div class="container bg-white rounded shadow mx-auto mt-2">
            <header class="p-4">
                <h3 class="text-lg font-bold">{{ category.name }}</h3>
                <p class="text-sm text-gray-600">Created {{ category.created_date }}</p>
                <p class="text-sm text-gray-600">Last updated {{ category.updated_date }}</p>
                <p>Slug : <span class="border bg-gray-200 text-gray-400 px-1">{{ category.slug }}</span></p>
                <control-buttons
                        :routes="routes"
                        :permissions="permissions"
                        class="mt-2"
                />
            </header>

            <section v-if="categoryProducts" class="p-4">
                <p class="text-sm text-gray-600">Products with this category :</p>

                <ul class=" list-reset flex flex-col">
                    <li
                            class="relative -mb-px block border p-4 border-grey"
                            v-for="(product, index) in productsList"
                            :key="index"
                    >
                        <inertia-link :href="$route('admin.products.show', {product : product.slug})">
                            {{ product.name }}
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

    import CategoryPermissions from '../../../Mixins/Admin/Categories/CategoryPermissions'
    import DefaultCrudRoutes from './../../../Mixins/Admin/Categories/DefaultCrudRoutes'

    export default {
        name: "show",

        props: {
            category: {
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
            CategoryPermissions,
            DefaultCrudRoutes
        ],

        data() {
            return {
                productsList: this.category.products
            }
        },

        computed: {
            title() {
                return `${this.category.name} details`
            },

            categoryProducts() {
                return this.productsList.length > 0
            }
        }
    }
</script>

<style scoped>

</style>
