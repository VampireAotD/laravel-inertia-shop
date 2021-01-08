export default {
    data() {
        return {
            permissions: {
                view: this.$can('view user'),
                changeRole : this.$can('change user role'),
                destroy: this.$can('delete user'),
            }
        }
    }
}
