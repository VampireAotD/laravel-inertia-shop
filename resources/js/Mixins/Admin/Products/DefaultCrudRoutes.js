export default {
    data() {
        return {
            routes: {
                view: this.$route('admin.products.show', {product: this.product.slug}),
                edit: this.$route('admin.products.edit', {product: this.product.slug}),
                destroy: this.$route('admin.products.destroy', {product: this.product}),
            }
        }
    },
}