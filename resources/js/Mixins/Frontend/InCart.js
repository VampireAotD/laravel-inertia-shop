export default {
    methods : {
        inCart({id}) {
            if (!this.$page.cart) {
                return false;
            }

            return this.$page.cart.includes(id);
        },
    }
}