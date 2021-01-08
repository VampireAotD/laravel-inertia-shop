<template>
    <div class="max-h-1/2 overflow-x-hidden overflow-y-auto">
        <div
            class="w-32 h-32 px-2"
            v-for="image in images"
        >
            <img
                :src="image.path"
                :alt="image.id"
                class="w-full object-cover"
                @click="$emit('set-main-image', image.path)"
            >
        </div>
    </div>
</template>

<script>
export default {
    name: "ProductImages",

    props: ['images'],
}
</script>

<style scoped>

</style>
