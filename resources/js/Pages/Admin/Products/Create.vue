<template>
    <admin-layout header-title="Create new product">
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

    export default {
        name: "create",

        props: {
            product: {
                type: Object,
                required: true
            },

            categoriesList : {
                type : Array,
                required: true
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            Loader,
            ProductForm
        },

        mixins : [ImagesMethods],

        data() {
            return {
                form: this.$inertia.form({
                    name: "",
                    slug: "",
                    price : "",
                    amount : "",
                    description : "",
                    images : this.product.images,
                    categoriesList : this.categoriesList,
                    categories : []
                })
            }
        }
    }
</script>

<style scoped>

</style>
