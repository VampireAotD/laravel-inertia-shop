export default {
    data() {
        return {
            routes : {
                view: this.$route('admin.roles.show', {role: this.role.id}),
                edit: this.$route('admin.roles.edit', {role: this.role.id}),
                destroy: this.$route('admin.roles.destroy', {role: this.role.id}),
            }
        }
    },
}
