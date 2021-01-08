export default {
    data() {
        return {
            routes : {
                view: this.$route('admin.permissions.show', {permission: this.permission.id}),
                edit: this.$route('admin.permissions.edit', {permission: this.permission.id}),
                destroy: this.$route('admin.permissions.destroy', {permission: this.permission.id}),
            }
        }
    },
}
