<template>
    <tr :class="numberInList % 2 ? null : 'bg-gray-100'">
        <td class="border px-4 py-2">{{ numberInList }}</td>

        <td class="border px-4 py-2">
            <inertia-link
                :href="$route('admin.users.show', {user : order.user.id})"
            >
                {{ order.user.name }}
            </inertia-link>
        </td>
        <td class="border px-4 py-2">
            <div class="w-full flex flex-wrap">
                <span class="w-1/3" v-for="product in order.ordered_products">
                    <inertia-link
                        :href="$route('admin.products.show', {product : product.slug})"
                    >
                        {{product.name}}
                    </inertia-link>
                </span>
            </div>
        </td>
        <td class="border px-4 py-2">{{ order.status | orderStatus }}</td>
        <td class="border px-4 py-2">{{ order.created_date }}</td>
        <td class="border px-4 py-2">
            <control-buttons
                :permissions="permissions"
                :routes="routes"
            >
                <template #additional-links>
                    <inertia-link
                        :href="routes.accept"
                        title="Accept order"
                        v-if="permissions.accept && !order.status"
                        class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4"
                    >
                        <svg data-v-ca99ed00="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" class="fill-current w-5 h-5">
                            <path data-v-ca99ed00="" fill-rule="evenodd"
                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </inertia-link>
                </template>
            </control-buttons>
        </td>
    </tr>
</template>

<script>
import DefaultCrudRoutes from '../../../../Mixins/Admin/Orders/DefaultCrudRoutes'
import OrderPermissions from "../../../../Mixins/Admin/Orders/OrderPermissions";
import ControlButtons from "../../../../Assets/Backend/ControlButtons";

export default {
    name: "order-info",

    props: {
        order: {
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
        DefaultCrudRoutes,
        OrderPermissions
    ],

    computed: {
        numberInList() {
            return this.number + 1
        }
    },

    filters: {
        orderStatus(status) {
            return status === 0 ? 'Proceeding' : 'Processed'
        }
    }
}
</script>

<style scoped>

</style>
