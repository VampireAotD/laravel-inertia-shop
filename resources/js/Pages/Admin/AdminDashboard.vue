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
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import AdminLayout from './../../Layouts/AdminLayout'
    import UserChart from './Charts/UserChart'
    import OrderChart from './Charts/OrderChart'

    export default {
        components: {
            AdminLayout,
            UserChart,
            OrderChart
        },

        props : ['usersPerMonth', 'ordersPerMonth'],

    }
</script>
