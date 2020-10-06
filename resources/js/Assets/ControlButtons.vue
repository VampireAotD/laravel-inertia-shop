<template>
    <div class="inline-flex">

        <inertia-link :href="routes.view" title="View" v-if="permissions.view"
                      class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded-l">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="fill-current w-5 h-5">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd"
                      d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                      clip-rule="evenodd"/>
            </svg>
        </inertia-link>

        <inertia-link :href="routes.edit" title="Update" v-if="permissions.update"
                      class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                 class="fill-current w-5 h-5">
                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                <path fill-rule="evenodd"
                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                      clip-rule="evenodd"/>
            </svg>
        </inertia-link>

        <slot name="additional-links"></slot>

        <form @submit.prevent="destroy" v-if="permissions.destroy">
            <button type="submit" title="Delete" class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded-r">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="fill-current w-5 h-5">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        </form>

    </div>
</template>

<script>
    export default {
        name: "control-buttons",

        props: {
            permissions: {
                type: Object,
                required: true
            },
            routes: {
                type: Object,
                required: true
            }
        },

        methods: {
            destroy() {
                this.$toast.question('Delete this item?', 'Delete', {
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    id: 'question',
                    zindex: 999,
                    position: 'center',
                    buttons: [
                        ['<button>Yes</button>', (instance, toast) => {

                            this.$inertia.delete(this.routes.destroy)

                            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                        }],
                        ['<button><b>No</b></button>', function (instance, toast) {

                            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

                        }, true],
                    ]
                });
            },
        }
    }
</script>

<style scoped>

</style>