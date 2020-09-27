<template>
    <tr :class="numberInList % 2 ? null : 'bg-gray-100'">
        <td class="border px-4 py-2">{{ numberInList }}</td>
        <td class="border px-4 py-2">
            <inertia-link :href="routes.view">
                {{ category.name }}
            </inertia-link>
        </td>
        <td class="border px-4 py-2">{{ category.created_date}}</td>
        <td class="border px-4 py-2">
            <control-buttons
                    :routes="routes"
            />
        </td>
    </tr>
</template>

<script>
    import ControlButtons from './../../../Assets/ControlButtons'
    import DefaultCrudRoutes from './../../../Mixins/Admin/Categories/DefaultCrudRoutes'

    export default {
        name: "category-info",

        props: {
            category: {
                type: Object,
                required: true
            },
            number: {
                type: Number
            }
        },

        components: {
            ControlButtons
        },

        mixins : [
            DefaultCrudRoutes
        ],

        computed: {
            numberInList() {
                return this.number + 1
            }
        }
    }
</script>

<style scoped>

</style>