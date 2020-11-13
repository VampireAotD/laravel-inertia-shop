<template>
    <form class="w-full max-w-5xl mx-auto" @submit.prevent="sendRequest" enctype="multipart/form-data">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    Name
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                       id="name" type="text" placeholder="Product name" autocomplete="off" v-model="form.name">
                <p class="text-red-500 text-xs" v-if="form.error('name')">{{ form.error('name') }}</p>
            </div>

            <div class="w-full px-3 mt-2">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="slug">
                    Slug
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                       id="slug" type="text" placeholder="Product slug" autocomplete="off" v-model="form.slug">
                <p class="text-red-500 text-xs" v-if="form.error('slug')">{{ form.error('slug') }}</p>
            </div>

            <div class="w-full px-3 mt-2">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="price">
                    Price
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                       id="price" type="number" placeholder="Product price" autocomplete="off" v-model="form.price">
                <p class="text-red-500 text-xs" v-if="form.error('price')">{{ form.error('price') }}</p>
            </div>

            <div class="w-full px-3 mt-2">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="amount">
                    Amount
                </label>
                <input class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                       id="amount" type="number" placeholder="Product amount" autocomplete="off" v-model="form.amount">
                <p class="text-red-500 text-xs" v-if="form.error('amount')">{{ form.error('amount') }}</p>
            </div>

            <div class="w-full px-3 mt-2">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                    Description
                </label>
                <div id="description">
                    <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
                </div>
                <p class="text-red-500 text-xs" v-if="form.error('description')">{{ form.error('description') }}</p>
            </div>

            <!--Image uploader-->
            <div class="w-full">
                <image-uploader
                        :images="form.images"
                        :multiple="true"
                        :permissions="permissions"
                        @file-uploaded="addFile"
                        @clear-images-from-uploaded="clearImagesFromUploaded"
                />
                <p class="text-red-500 text-xs" v-if="form.error('images')">{{ form.error('images') }}</p>
            </div>

            <div class="w-full px-3 mt-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Categories
                </label>
                <label class="inline-flex items-center mt-3 mr-3" v-for="category in form.categoriesList">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600" @change="syncWithCategories"
                           :value="category.id" :checked="form.categories.includes(category.id)">
                    <span class="ml-2 text-gray-700">{{ category.name }}</span>
                </label>
                <p class="text-red-500 text-xs" v-if="form.error('categories')">{{ form.error('categories') }}</p>
            </div>

            <div class="w-full px-3 mt-2 flex justify-center">
                <input class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded shadow-md cursor-pointer"
                       :disabled="processing"
                       type="submit" value="Submit">
                <inertia-link
                        :href="$route($page.previousRoute, $page.previousRouteParameters)"
                        :disabled="processing"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 border border-green-700 rounded shadow-md cursor-pointer ml-2">
                    Cancel
                </inertia-link>
            </div>
        </div>
    </form>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import ImageUploader from '../../../../Assets/Backend/ImageUploader'

    export default {
        name: "product-form",

        props: ['form', 'product', 'mode', 'permissions'],

        components: {
            ImageUploader
        },

        data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    height: '800px'
                }
            }
        },

        methods: {
            sendRequest() {
                if (this.mode === 'edit') {
                    this.form.post(this.$route('admin.products.update', {product: this.product.slug}), {
                        preserveScroll: true
                    })
                } else {
                    this.form.post(this.$route('admin.products.store'))
                }
            },

            syncWithCategories(e) {
                let value = +e.target.value
                e.target.checked ? this.form.categories.push(value) : this.form.categories.splice(this.form.categories.indexOf(value), 1)
                this.form.categories = [...new Set(this.form.categories)]
            },

            addFile(files) {
                this.$emit('file-uploaded', files)
            },

            clearImagesFromUploaded() {
                this.$emit('clear-images-from-uploaded')
            }
        }
    }
</script>

<style scoped>

</style>