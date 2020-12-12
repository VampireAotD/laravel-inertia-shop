<script>
    import {Bar} from 'vue-chartjs'

    export default {
        name: "order-chart",

        props: ['perMonth'],

        extends: Bar,

        data() {
            return {
                chartData: {
                    label: 'Orders',
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [
                        {
                            label: 'Orders',
                            backgroundColor: '#33d45e',
                            data: this.perMonth
                        }
                    ]
                }
            }
        },

        mounted() {
            // Overwriting base render method with actual data.
            this.renderChart(this.chartData)
        }
    }
</script>

<style scoped>

</style>