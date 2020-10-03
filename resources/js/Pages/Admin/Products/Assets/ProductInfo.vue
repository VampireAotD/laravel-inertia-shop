<template>
    <tr :class="numberInList % 2 ? null : 'bg-gray-100'">
        <td class="border px-4 py-2">{{ numberInList }}</td>
        <td class="border px-4 py-2 w-32 max-w-sm">
            <img :src="product.main_image_path" :alt="product.name" class="object-contain">
        </td>
        <td class="border px-4 py-2">
            <inertia-link :href="routes.view">
                {{ product.name }}
            </inertia-link>
        </td>
        <td class="border px-4 py-2">{{ product.excerpt | description }}</td>
        <td class="border px-4 py-2">{{ product.price }}</td>
        <td class="border px-4 py-2">{{ product.amount}} {{ product.count_amount }}</td>
        <td class="border px-4 py-2">{{ product.created_date }}</td>
        <td class="border px-4 py-2">
            <control-buttons
                    :routes="routes"
            />
        </td>
    </tr>
</template>

<script>
    import ControlButtons from '../../../../Assets/ControlButtons'
    import DefaultCrudRoutes from '../../../../Mixins/Admin/Products/DefaultCrudRoutes'

    export default {
        name: "product-info",

        props: {
            product: {
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

        mixins: [
            DefaultCrudRoutes
        ],

        computed: {
            numberInList() {
                return this.number + 1
            }
        },

        filters: {
            description(value) {
                return value === '' ? 'No description...' : value
            }
        },
    }
</script>

<style scoped>

</style>