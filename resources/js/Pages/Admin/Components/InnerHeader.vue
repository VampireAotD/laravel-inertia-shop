<template>
    <div class="py-5 flex flex-col">
        <div class="flex w-full">
            <inertia-link :href="route"
                          :class="classes"
                          v-if="route && title"
            >
                {{ title }}
            </inertia-link>
        </div>

        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "inner-header",

    props: {
        route: {
            type: String,
            default: '#'
        },

        classes: {
            type: String,
            default: 'bg-transparent hover:bg-blue-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded'
        },

        title: {
            type: String
        },
    }
}
</script>

<style scoped>

</style>
