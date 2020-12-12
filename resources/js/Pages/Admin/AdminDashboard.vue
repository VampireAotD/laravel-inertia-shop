<template>
    <admin-layout header-title="Admin panel">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-center">Admin panel</h1>

                <hr>

                <div class="flex mt-2 h-full">
                    <div class="bg-white w-1/3 h-full overflow-hidden py-4 px-4">
                        <user-chart :per-month="usersPerMonth"/>
                    </div>

                    <div class="bg-white w-1/3 h-full overflow-hidden py-4 px-4">
                        <order-chart :per-month="ordersPerMonth"/>
                    </div>
                </div>

                <hr>

                <div class="flex mt-2 h-full">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow overflow-hidden bg-white shadow-dashboard px-8 pt-3 rounded-bl-lg rounded-br-lg">
                            <table class="min-w-full">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">ID</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Time</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-blue-500 tracking-wider">Page</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr v-for="user in users">
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm leading-5 text-gray-800">
                                                    <inertia-link :href="$route('admin.users.show', {user : user.id})">
                                                        {{user.id}}
                                                    </inertia-link>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                        <div class="text-sm leading-5 text-blue-900">{{user.date}}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b text-blue-900 border-gray-500 text-sm leading-5">
                                        {{ user.page }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../Layouts/AdminLayout'
    import UserChart from './Components/Charts/UserChart'
    import OrderChart from './Components/Charts/OrderChart'

    export default {
        components: {
            AdminLayout,
            UserChart,
            OrderChart
        },

        props : ['usersPerMonth', 'ordersPerMonth'],

        data() {
            return {
                users : []
            }
        },

        created(){
            this.$socket.onmessage = message => {
                this.users = JSON.parse(message.data)
            }
        }
    }
</script>
