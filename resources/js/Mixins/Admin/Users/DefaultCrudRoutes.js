export default {
    data() {
        return {
            routes: {
                view: this.$route('admin.users.show', {user: this.user}),
                changeRole : this.$route('admin.users.change-role', {user : this.user}),
                destroy: this.$route('admin.users.destroy', {user: this.user}),
            }
        }
    },
}