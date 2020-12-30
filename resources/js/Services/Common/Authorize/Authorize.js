export default {
    install(Vue) {
        Vue.prototype.$can = function (permissionName) {
            return this.$page.permissions.hasOwnProperty(permissionName) && this.$page.permissions[permissionName]
        };

        Vue.prototype.$hasRole = function (roleName) {
            return this.$page.user.role === roleName
        };
    }
}
