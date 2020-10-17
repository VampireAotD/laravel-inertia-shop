<template>
    <tr :class="numberInList % 2 ? null : 'bg-gray-100'">
        <td class="border px-4 py-2">{{ numberInList }}</td>
        <td class="border px-4 py-2 w-32 h-16 max-w-sm">
            <img :src="user.profile_photo_url" :alt="user.name" class="object-cover">
        </td>
        <td class="border px-4 py-2">
            <inertia-link :href="routes.view">
                {{ user.name }}
            </inertia-link>
        </td>
        <td class="border px-4 py-2">{{ user.email }}</td>
        <td class="border px-4 py-2">{{ user.role }}</td>
        <td class="border px-4 py-2">{{ user.orders_count }}</td>
        <td class="border px-4 py-2">{{ user.created_date }}</td>
        <td class="border px-4 py-2">
            <control-buttons
                    :routes="routes"
                    :permissions="permissions"
            />
        </td>
    </tr>
</template>

<script>
    import ControlButtons from '../../../../Assets/ControlButtons'
    import DefaultCrudRoutes from '../../../../Mixins/Admin/Users/DefaultCrudRoutes'

    export default {
        name: "user-info",

        props: {
            user: {
                type: Object,
                required: true
            },
            permissions: {
                type: Object
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
        }
    }
</script>

<style scoped>

</style>