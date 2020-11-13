<template>
    <form @submit.prevent="removeItem" class="mt-2">
        <div class="flex mb-2 px-4 relative">
            <inertia-link href="#" class="w-2/5 h-48">
                <img class="max-w-full max-h-full object-cover" :src="item.main_image_path" :alt="item.name">
            </inertia-link>

            <div class="w-3/5 flex flex-col">
                <inertia-link href="#">
                    {{ item.name }}
                </inertia-link>

                {{item.excerpt}}

                <p>{{ item.price }}</p>

                <p>{{ item.created_date }}</p>

                <button class="absolute top-0 right-0">
                    <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                                d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <hr>
    </form>
</template>

<script>
    export default {
        name: "item",

        props: ['item'],

        data(){
            return {
                removeItemForm : this.$inertia.form({
                    '_method' : 'DELETE',
                    product : this.item.slug
                }),
            }
        },

        methods : {
            removeItem(){
                this.removeItemForm.delete(this.$route('remove-from-favorite', {product : this.removeItemForm.product}),{
                    preserveScroll : true,
                })
            }
        }
    }
</script>

<style scoped>

</style>