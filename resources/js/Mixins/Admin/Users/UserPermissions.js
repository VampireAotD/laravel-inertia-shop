export default {
    data() {
        return {
            permissions: {
                view: this.$can('see one user'),
                changeRole : this.$can('change user role'),
                destroy: this.$can('delete user'),
            }
        }
    }
}