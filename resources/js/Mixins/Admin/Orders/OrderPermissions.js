export default {
    data() {
        return {
            permissions: {
                accept : this.$can('accept order'),
                cancel: this.$can('cancel order'),
                view: this.$can('view order'),
                destroy: this.$can('delete order'),
            }
        }
    }
}
