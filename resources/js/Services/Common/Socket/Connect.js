export default {
    install(Vue) {
        const user = JSON.parse(app.dataset.page).props.user?.id;

        Vue.prototype.$socket = new WebSocket(`ws://localhost:8000?user_id=${user}&on=${window.location.href}`)
    }
}
