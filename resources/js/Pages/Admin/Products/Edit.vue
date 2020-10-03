<template>
    <admin-layout :header-title="title">

        <loader v-if="form.processing"/>

        <div class="py-5">
            <inertia-link :href="$route('admin.products.index')"
                          class="bg-transparent hover:bg-blue-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                Products list
            </inertia-link>
        </div>

        <hr>

        <div class="container mx-auto mt-5">
            <product-form
                    :form="form"
                    :product="product"
                    mode="edit"
                    @file-uploaded="refreshImagesArray"
                    @clear-images-from-uploaded="clearImagesFromUploaded"
            />
        </div>

    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import Loader from './../../../Assets/Loader'
    import ProductForm from './Assets/ProductForm'

    import ImagesMethods from './../../../Mixins/Admin/Products/ImagesArrayMethods'

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
            Loader,
            ProductForm
        },

        mixins : [ImagesMethods],

        data() {
            return {
                form: this.$inertia.form({
                    '_method' : 'PUT',
                    name: this.product.name,
                    slug: this.product.slug,
                    price : this.product.price,
                    amount : this.product.amount,
                    description : this.product.description,
                    images : this.product.images,
                    categoriesList : this.categoriesList,
                    categories : this.product.categories.map( category => category.id),
                })
            }
        },

        computed : {
            title(){
                return `Edit ${this.product.name}`
            }
        }
    }
</script>

<style scoped>

</style>