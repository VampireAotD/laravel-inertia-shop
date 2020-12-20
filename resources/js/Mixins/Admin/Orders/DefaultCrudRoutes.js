export default {
    data() {
        return {
            routes: {
                view : this.$route('admin.orders.show', {user : this.order.user, date : this.order.created_at}),
                accept : this.$route('admin.orders.accept', {order : this.order.id}),
            }
        }
    },
}
