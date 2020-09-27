export default {
    data() {
        return {
            routes : {
                view: this.$route('admin.categories.show', {category: this.category.slug}),
                edit: this.$route('admin.categories.edit', {category: this.category.slug}),
                destroy: this.$route('admin.categories.destroy', {category: this.category}),
            }
        }
    },
}