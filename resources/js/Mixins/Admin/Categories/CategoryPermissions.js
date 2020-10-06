export default {
    data() {
        return {
            permissions: {
                create: this.$can('create category'),
                update: this.$can('edit category'),
                view: this.$can('see one category'),
                destroy: this.$can('delete category'),
            }
        }
    }
}