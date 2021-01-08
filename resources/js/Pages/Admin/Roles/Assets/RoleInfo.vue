<template>
    <tr :class="numberInList % 2 ? null : 'bg-gray-100'">
        <td class="border px-4 py-2">{{ numberInList }}</td>
        <td class="border px-4 py-2">
            <inertia-link :href="routes.view">
                {{ role.name }}
            </inertia-link>
        </td>
        <td class="border px-4 py-2">{{ role.created_date }}</td>
        <td class="border px-4 py-2">
            <control-buttons
                    :routes="routes"
                    :permissions="permissions"
            />
        </td>
    </tr>
</template>

<script>
    import ControlButtons from '../../../../Assets/Backend/ControlButtons'
    import DefaultCrudRoutes from "../../../../Mixins/Admin/Roles/DefaultCrudRoutes";

    export default {
        name: "role-info",

        props: {
            role: {
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
