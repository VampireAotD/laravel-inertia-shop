export default {
    data() {
        return {
            permissions: {
                create: this.$can('create permission'),
                update: this.$can('edit permission'),
                view: this.$can('view permission'),
                destroy: this.$can('delete permission'),
            }
        }
    }
}
