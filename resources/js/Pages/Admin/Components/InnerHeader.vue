<template>
    <div class="py-5 flex flex-col">
        <div class="flex w-full">
            <inertia-link :href="$route(route)"
                          :class="classes">
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
                required: true
            },
            classes: {
                type: String
            },
            title: {
                type: String,
                required: true
            },
        }
    }
</script>

<style scoped>

</style>