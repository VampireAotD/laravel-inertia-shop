export default {
    data() {
        return {
            permissions: {
                accept : this.$can('accept one order'),
                cancel: this.$can('cancel one order'),
                view: this.$can('see one order'),
                destroy: this.$can('delete order'),
            }
        }
    }
}
