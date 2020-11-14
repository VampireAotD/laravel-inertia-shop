export default {
    install(Vue) {
        Vue.prototype.$can = function (permissionName) {
            return this.$page.permissions.indexOf(permissionName) !== -1
        };

        Vue.prototype.$hasRole = function (roleName) {

            let result = this.$page.user.roles.map(role => {
                return role.name === roleName
            })

            return result.shift()
        };
    }
}