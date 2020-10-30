<script>
    import { Bar } from 'vue-chartjs'

    export default {
        name: "user-chart",

        props : ['perMonth'],

        extends: Bar,

        data(){
            return {
                chartData : {
                    label : 'Users',
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [
                        {
                            label: 'Registered users',
                            backgroundColor: '#f87979',
                            data: this.perMonth
                        }
                    ]
                }
            }
        },

        mounted () {
            // Overwriting base render method with actual data.
            this.renderChart(this.chartData)
        }
    }
</script>

<style scoped>

</style>