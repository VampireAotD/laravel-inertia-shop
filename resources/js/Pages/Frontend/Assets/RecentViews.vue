<template>
    <swiper class="swiper" :options="swiperOption">
        <swiper-slide
            v-for="(product, index) in products"
            :key="index"
            class="container mx-auto flex items-center flex-wrap pt-4 pb-12"
        >
            <div class="w-full">
                <inertia-link :href="$route('product', {product : product.slug})">
                    <div class="h-48">
                        <img class="max-h-full object-cover hover:grow hover:shadow-lg"
                             :src="product.main_image_path">
                    </div>
                    <div class="pt-3 flex items-center justify-between">
                        <p>{{ product.name }}</p>
                    </div>
                    <p class="pt-1 text-gray-900">{{ product.price }}</p>
                </inertia-link>
            </div>
        </swiper-slide>
    </swiper>
</template>

<script>
export default {
    name: 'recent-views',

    props: ['products'],

    data() {
        return {
            swiperOption: {
                slidesPerView: 3,
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
