<template>
    <form @submit.prevent class="flex items-center relative w-2/6">
        <input type="search"
               class="border-2 w-full border-gray-300 bg-white h-9 px-5 rounded-lg text-sm focus:outline-none"
               v-model="searchQuery"
               :placeholder="__('header.search')"
               minlength="3"
        >

        <div class="absolute top-36 left-0 z-50 w-full" v-if="searchResults.hits && showDropDown">
            <div class="rounded shadow-md my-2 pin-t pin-l bg-white">
                <ul class="list-reset">
                    <li v-for="product in searchResults.hits.hits">
                        <p class="p-2 block text-black hover:bg-grey-light cursor-pointer">
                            <inertia-link :href="$route('product', {product : product._source.slug})">
                                <div class="flex">
                                    <div class="w-1/4 object-cover">
                                        <img :src="product._source.main_image_path" :alt="product._source.name">
                                    </div>
                                    <div class="px-2">
                                        <p>{{product._source.name}}</p>
                                        <span>{{product._source.price}}</span>
                                    </div>
                                </div>
                            </inertia-link>
                            <hr>
                        </p>
                    </li>

                    <!--Suggest-->
                    <li
                            v-if="searchResults.suggest.product_suggest[0].options.length > 0"
                            class="border-t-2 border-gray-600"
                    >
                        <p
                            class="p-2 block text-black hover:bg-grey-light cursor-pointer"
                            v-if="searchResults.suggest.product_suggest[0].options[0]"
                        >
                            <span @click="searchQuery = searchResults.suggest.product_suggest[0].options[0].text">
                                Maybe you mean <em> {{searchResults.suggest.product_suggest[0].options[0].text}} </em> ?
                            </span>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        name: "search",

        data() {
            return {
                searchQuery : '',
                searchResults : {},
                showDropDown : true,
            }
        },

        methods: {
            async search(value) {
                let response = await axios.post(this.$route('search'), {term: value})
                this.searchResults = response.data
            }
        },

        watch: {
            searchQuery(value) {
                if (value.trim().length >= 3) {
                    this.search(value)
                } else {
                    this.searchResults = {}
                }
            }
        }
    }
</script>

<style scoped>

</style>
