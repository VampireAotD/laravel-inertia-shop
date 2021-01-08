export default {
    data() {
        return {
            permissions: {
                create : this.$can('create product'),
                update: this.$can('edit product'),
                view: this.$can('view product'),
                destroy: this.$can('delete product'),
            }
        }
    }
}
