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
                default : '#'
            },
            classes: {
                type: String
            },
            title: {
                type: String
            },
        }
    }
</script>

<style scoped>

</style>