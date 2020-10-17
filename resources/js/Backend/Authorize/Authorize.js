export default {
    install(Vue) {
        Vue.prototype.$can = function (permissionName) {
            return this.$page.permissions.indexOf(permissionName) !== -1
        };

        Vue.prototype.$hasRole = function (roleName) {
            return this.$page.user.roles.map(role => {
                return role.name === roleName
            }).length > 0 ?? false
        };
    }
}