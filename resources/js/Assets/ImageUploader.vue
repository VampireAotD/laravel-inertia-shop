<template>
    <div class="file-uploader px-3 my-5 w-full">

        <div class="images-preview flex justify-center flex-wrap mb-5">

            <!--Images display-->

            <div class="w-1/4 h-48 m-1 lg:shadow-xl" v-for="(image, index) in images">
                <div class="image w-full h-full max-w-full max-h-full relative"
                     v-if="typeof image === 'object' && image.model_type">
                    <img :src='image.path'
                         class="min-w-0 min-h-0 max-w-full w-full h-full object-cover cursor-pointer">

                    <!--Image controls-->

                    <div class="inline-flex items-center justify-center image-controls">
                        <inertia-link :href="$route('admin.images.update-main-image', {image})"
                                      title="Set this image as main image"
                                      class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 rounded-l"
                                      v-if="image.is_main !== 1 && $can('update product main image')"
                                      preserve-scroll>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="fill-current w-5 h-5">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </inertia-link>

                        <form @submit.prevent="deleteImage(image)" v-if="$can('remove product images')">
                            <button type="submit" title="Delete this image"
                                    class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded-r">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     class="fill-current w-5 h-5">
                                    <path fill-rule="evenodd"
                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!--Image controls end-->

                <!--Recent uploaded images-->

                <div class="w-full h-full max-w-full max-h-full relative" v-else>
                    <img :src='image | createImageUrl'
                         class="min-w-0 min-h-0 max-w-full w-full h-full object-cover cursor-pointer">
                    <button @click.prevent="removeImage(index)" class="text-lg text-red-500 absolute top-0 right-2 z-50"
                            title="Remove from list">
                        x
                    </button>
                </div>

            </div>

        </div>

        <!--Upload input-->
        <div class="flex w-full items-center justify-center bg-grey-lighter ">
            <label class="w-64 flex flex-col items-center p-2 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-black hover:text-white mr-2">
                <svg class="w-8 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z"/>
                </svg>
                <input type='file' class="hidden" name="images[]" accept="image/*" @input="addToFilesArray"
                       :multiple="multiple"/>
            </label>
            <button class="w-64 p-2 bg-white text-red rounded-lg shadow-lg tracking-wide uppercase border border-red-300 cursor-pointer hover:bg-red-400 hover:text-white"
                    @click.prevent="resetImagesArray" v-if="issetImages">
                Reset
            </button>
        </div>

    </div>
</template>

<script>
    export default {
        name: "image-uploader",

        props: {
            images: {
                type: Array
            },
            multiple: {
                type: Boolean
            },
            permissions: {
                type: Object
            }
        },

        methods: {
            addToFilesArray(e) {
                let files = e.target.files

                if (this.multiple) {
                    [...files].map(image => {
                        if (this.validateImage(image)) {
                            this.addImage(image)
                        }
                    })
                } else {
                    if (this.validateImage(files[0])) {
                        this.addImage(files[0])
                    }
                }
            },

            validateImage(image) {
                return image && image.type.match('image/*');
            },

            addImage(image) {
                this.images.push(image)
                this.$emit('file-uploaded', this.images)
            },

            resetImagesArray() {
                this.$emit('clear-images-from-uploaded')
            },

            removeImage(id) {
                this.images.splice(id, 1)
            },

            deleteImage(image) {
                this.$inertia.delete(this.$route('admin.images.destroy-image', {image}), {
                    preserveScroll: true
                })
            }
        },

        filters: {
            createImageUrl(image) {
                return URL.createObjectURL(image)
            }
        },

        computed: {
            issetImages() {
                return this.images.length > 0 && !this.images.every(image => image.hasOwnProperty('path'))
            }
        }
    }
</script>

<style scoped>
    .image-controls {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.5);
        width: 100%;
        height: 100%;
        color: white;
    }

    .image:hover .image-controls {
        display: inline-flex;
    }
</style>