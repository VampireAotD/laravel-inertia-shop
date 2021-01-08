export default {
    data() {
        return {
            permissions: {
                create: this.$can('create role'),
                update: this.$can('edit role'),
                view: this.$can('view role'),
                destroy: this.$can('delete role'),
            }
        }
    }
}
