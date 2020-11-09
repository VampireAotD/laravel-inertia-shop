export default {
    install(Vue, options) {
        const user = JSON.parse(app.dataset.page).props.user?.id;

        let socket = new WebSocket(`ws://localhost:8000?user_id=${user}`)

        socket.onmessage = function(message) { // TODO : do a socket connection
            /*let connections = [];

            connections = message.data;

            Vue.prototype.$conn = connections*/
        }
    }
}