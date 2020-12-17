export default {
    methods : {
        inCart({id}) {
            if (!this.$page.cart) {
                return false;
            }

            return this.$page.cart.includes(id);
        },

        inFavoriteList({id}) {
            if (!this.$page.favorite_list) {
                return false;
            }

            return this.$page.favorite_list.includes(id);
        },
    }
}
