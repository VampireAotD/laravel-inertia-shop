<template>
    <admin-layout :header-title="title">

        <inner-header
                :route="$route('admin.products.index')"
                title="Products list"
        />

        <hr>

        <div class="container bg-white rounded shadow mx-auto mt-2">

            <header class="p-4">
                <div class="w-48 h-48 mb-5">
                    <img :src="product.main_image_path" :alt="product.name" class="object-cover max-w-fll max-h-full">
                </div>

                <h3 class="text-lg font-bold">{{ product.name }}</h3>
                <p class="text-sm text-gray-600">Created {{ product.created_date }}</p>
                <p class="text-sm text-gray-600">Last updated {{ product.updated_date }}</p>
                <p>ID : <span class="border bg-gray-200 text-gray-400 px-1">{{ product.id }}</span></p>
                <p>Slug : <span class="border bg-gray-200 text-gray-400 px-1">{{ product.slug }}</span></p>
                <control-buttons
                        :routes="routes"
                        class="mt-4"
                        :permissions="permissions"
                />
            </header>

            <section class="p-4">

                <!--Slider-->
                <div class="sm:container mx-auto px-4 mb-8" v-if="imagesListLength">
                    <viewer :images="imagesList" class="flex flex-wrap justify-center">
                        <div class="w-3/12 h-64 m-1" v-for="(image, index) in imagesList">
                            <img
                                    :src="image.path"
                                    :key="index"
                                    :alt="`${product.name}-image-${index}`"
                                    class="object-cover h-full w-full"
                            >
                        </div>
                    </viewer>
                </div>

                <div class="text-gray-300 p-5" v-else>
                    <h3>No images for this product...</h3>
                </div>
                <!--Slider end-->

                <hr>

                <!--Categories list-->
                <div class="categories mt-4" v-if="productCategories">
                    <p class="text-sm text-gray-600">Categories in this product :</p>

                    <ul class=" list-reset flex flex-col">
                        <li
                                class="relative -mb-px block border p-4 border-grey"
                                v-for="(category, index) in product.categories"
                                :key="index"
                        >
                            <inertia-link :href="$route('admin.categories.show', {category : category.slug})">
                                {{ category.name }}
                            </inertia-link>
                        </li>
                    </ul>
                </div>
                <!--Categories list end-->

                <!--Orders list-->
                <div class="categories mt-4">
                    <p class="text-sm text-gray-600">Users that ordered this product :</p>

                    <ul class=" list-reset flex flex-col">
                        <li
                                class="relative -mb-px block border p-4 border-grey"
                                v-for="(order, index) in product.orders"
                                :key="index"
                        >
                            <inertia-link :href="$route('admin.users.show', {user : order.users[0]})">
                                {{ order.users[0].name }}
                            </inertia-link>
                        </li>
                    </ul>
                </div>
                <!--Orders list end-->

                <!--Description-->
                <div class="description mt-4" v-if="!issetDescription">
                    <h3>Description</h3>

                    <div class="full-description" v-if="showDescription">
                        {{ product.html_description }}
                    </div>

                    <div class="excerpt" v-else>
                        {{ product.excerpt }}
                    </div>

                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full cursor-pointer outline-none"
                            @click="showFullDescription">
                        {{ buttonTitle }}
                    </button>
                </div>
                <!--Description end-->
            </section>

        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import ControlButtons from '../../../Assets/Backend/ControlButtons'

    import DefaultCrudRoutes from './../../../Mixins/Admin/Products/DefaultCrudRoutes'
    import ProductPermissions from '../../../Mixins/Admin/Products/ProductPermissions'

    export default {
        name: "show",

        props: {
            product: {
                type: Object,
                required: true,
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            ControlButtons,
        },

        mixins: [
            DefaultCrudRoutes,
            ProductPermissions
        ],

        data() {
            return {
                categoriesList: this.product.categories,

                imagesList: this.product.images,

                showDescription: false,

                sliderSettings: {
                    arrows: true,
                    adaptiveHeight: true,
                    dots: true,
                    fade: true,
                    swipe: true,
                    centerMode: true,
                    centerPadding: "20px",
                    focusOnSelect: true,
                    infinite: true,
                    slidesToShow: 3,
                    speed: 500,
                    autoplay: true
                }
            }
        },

        methods: {
            showFullDescription() {
                this.showDescription = !this.showDescription
            }
        },

        computed: {
            title() {
                return `${this.product.name} details`
            },

            productCategories() {
                return this.categoriesList.length > 0
            },

            imagesListLength() {
                return this.imagesList.length > 0
            },

            buttonTitle() {
                return this.showDescription ? 'Show excerpt' : 'Show description'
            },

            issetDescription() {
                return this.product.html_description === ''
            }
        }
    }
</script>

<style scoped>

</style>
