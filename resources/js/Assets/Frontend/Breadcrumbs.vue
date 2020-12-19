<template>
    <nav class="container mx-auto bg-grey-light p-3 rounded font-sans w-full m-4">
        <ol class="list-reset flex text-grey-dark">

            <li v-for="breadcrumb in breadcrumbs">
                <inertia-link
                    class="text-blue font-bold"
                    :href="breadcrumb.url"
                    v-if="!currentPage(breadcrumb.url)"
                >
                    {{breadcrumb.title}}

                    <span class="mx-2">/</span>
                </inertia-link>

                <span v-else>{{breadcrumb.title}}</span>
            </li>

        </ol>
    </nav>
</template>

<script>
export default {
    name: "Breadcrumbs",

    props: {
        breadcrumbs : {
            type : Array,
            required : true
        }
    },

    methods : {
        currentPage(url){
            return window.location.href === url
        }
    },
}
</script>

<style scoped>

</style>
