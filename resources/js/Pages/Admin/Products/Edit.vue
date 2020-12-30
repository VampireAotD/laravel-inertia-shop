<template>
    <admin-layout :header-title="title">

        <loader
                v-if="form.processing"
        />

        <inner-header
                :route="$route('admin.products.index')"
                title="Products list"
        />

        <hr>

        <div class="container mx-auto mt-5">
            <product-form
                    :form="form"
                    :product="product"
                    :permissions="permissions"
                    mode="edit"
                    @file-uploaded="refreshImagesArray"
                    @clear-images-from-uploaded="clearImagesFromUploaded"
            />
        </div>

    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import Loader from '../../../Assets/Backend/Loader'
    import ProductForm from './Assets/ProductForm'

    import ImagesMethods from './../../../Mixins/Admin/Products/ImagesArrayMethods'
    import ProductPermissions from './../../../Mixins/Admin/Products/ProductPermissions'

    export default {
        name: "edit",

        props: {
            product: {
                type: Object,
                required: true
            },
            categoriesList: {
                type: Array,
                required: true
            },
        },

        components: {
            AdminLayout,
            InnerHeader,
            Loader,
            ProductForm
        },

        mixins: [
            ImagesMethods,
            ProductPermissions
        ],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.product.name,
                    slug: this.product.slug,
                    price: this.product.price,
                    amount: this.product.amount,
                    description: this.product.description,
                    images: this.product.images,
                    categoriesList: this.categoriesList,
                    categories: this.product.categories.map(category => category.id),
                })
            }
        },

        computed: {
            title() {
                return `Edit ${this.product.name}`
            }
        }
    }
</script>

<style scoped>

</style>
