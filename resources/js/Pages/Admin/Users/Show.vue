<template>
    <admin-layout :header-title="title">

        <inner-header
                :route="$route('admin.users.index')"
                title="Users list"
                classes="bg-transparent hover:bg-blue-500 text-white-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
        />

        <hr>

        <div class="container bg-white rounded shadow mx-auto mt-2">
            <header class="p-4">
                <div class="w-48 h-48 mb-5">
                    <img :src="user.profile_photo_url" :alt="user.name" class="object-cover max-w-fll max-h-full">
                </div>

                <h3 class="text-lg font-bold">{{ user.name }}</h3>
                <p class="text-sm text-gray-600">Created {{ user.created_date }}</p>
                <p class="text-sm text-gray-600">Last updated {{ user.updated_date }}</p>
                <p>Role : <span
                        class="border bg-gray-200 text-gray-400 px-1">{{ user.roles[0] ? user.role : 'User' }}</span>
                </p>

                <control-buttons
                        :routes="routes"
                        :permissions="permissions"
                        class="mt-2"
                >
                    <template #additional-links>
                        <inertia-link href="#" title="Change user role" v-if="permissions.changeRole"
                                      @click.prevent="changeUserRole = !changeUserRole"
                                      class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="fill-current w-5 h-5">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                <path fill-rule="evenodd"
                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </inertia-link>
                    </template>
                </control-buttons>
            </header>

            <section v-if="userOrders" class="p-4">
                <p class="text-sm text-gray-600">User orders :</p>
                <ul class=" list-reset flex flex-col">
                    <li
                            class="relative -mb-px block border p-4 border-grey"
                            v-for="(order, index) in ordersList"
                            :key="index"
                    >
                        <inertia-link href="#">
                            Order #{{ order.id }}
                        </inertia-link>
                    </li>
                </ul>
            </section>

        </div>

        <jet-dialog-modal :show="changeUserRole" @close="changeUserRole = false">
            <template #title>
                Change user role
            </template>

            <template #content>
                <form class="w-full max-w-lg mx-auto" @submit.prevent="changeRole">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <select class="form-select block w-full mt-1" v-model="form.role">
                                <option value="0" selected>Choose role</option>
                                <option
                                        v-for="role in $page.allRoles"
                                        :selected="user.roles[0] ? (role.id === user.roles[0].id) : null"
                                        :value="role.id"
                                >
                                    {{ role.name | ucFirst }}
                                </option>
                            </select>
                        </div>

                        <div class="w-full px-3 mt-2 flex justify-center">
                            <input class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded shadow-md cursor-pointer"
                                   type="submit" value="Submit">
                            <button type="button" @click="changeUserRole = false"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 border border-green-700 rounded shadow-md cursor-pointer ml-2">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </template>
        </jet-dialog-modal>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../../Layouts/AdminLayout'
    import InnerHeader from './../Components/InnerHeader'
    import ControlButtons from './../../../Assets/ControlButtons'
    import JetDialogModal from '../../../Jetstream/DialogModal'

    import UserPermissions from '../../../Mixins/Admin/Users/UserPermissions';
    import DefaultCrudRoutes from '../../../Mixins/Admin/Users/DefaultCrudRoutes';

    export default {
        name: "show",

        props: {
            user: {
                type: Object,
                required: true
            }
        },

        components: {
            AdminLayout,
            InnerHeader,
            ControlButtons,
            JetDialogModal
        },

        mixins: [
            UserPermissions,
            DefaultCrudRoutes
        ],

        data() {
            return {
                ordersList: this.user.orders,
                changeUserRole: false,
                form: this.$inertia.form({
                    '_method': 'patch',
                    role: this.user.roles[0]?.id ?? 0
                })
            }
        },

        methods: {
            changeRole() {
                this.form.patch(this.$route('admin.users.change-role', {user: this.user.id}), {
                    preserveScroll: false,
                }).finally(() => {
                    this.changeUserRole = false
                })
            }
        },

        computed: {
            title() {
                return `${this.user.name} details`
            },

            userOrders() {
                return this.ordersList.length > 0
            }
        },

        filters: {
            ucFirst(value) {
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        }
    }
</script>

<style scoped>

</style>