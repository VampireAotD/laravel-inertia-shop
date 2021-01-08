<template>
    <form class="w-full max-w-lg mx-auto" @submit.prevent="sendRequest">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2 required" for="name">
                    Name
                </label>
                <input
                    class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="name" type="text" placeholder="Role name" autocomplete="off" v-model="form.name">
                <p class="text-red-500 text-xs" v-if="form.error('name')">{{ form.error('name') }}</p>
            </div>

            <div class="w-full px-3 mt-3">
              <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                Permissions
              </label>
              <label class="inline-flex items-center mt-3 mr-3" v-for="permissions in form.permissionsList">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600" @change="syncWithPermissions"
                       :value="permissions.id" :checked="form.permissions.includes(permissions.id)">
                <span class="ml-2 text-gray-700">{{ permissions.name }}</span>
              </label>
              <p class="text-red-500 text-xs" v-if="form.error('permissions')">{{ form.error('permissions') }}</p>
            </div>

            <div class="w-full px-3 mt-2 flex justify-center">
                <input
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded shadow-md cursor-pointer"
                    type="submit" value="Submit" :disabled="form.processing">
                <inertia-link
                    :href="$route($page.previousRoute, $page.previousRouteParameters)"
                    :disabled="form.processing"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 border border-green-700 rounded shadow-md cursor-pointer ml-2">
                    Cancel
                </inertia-link>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "role-form",

    props: ['form', 'mode', 'permissionsList'],

    methods: {
        sendRequest() {
            if (this.mode === 'edit') {
                this.form.put(this.$route('admin.roles.update', {role: this.form.id}))
            } else {
                this.form.post(this.$route('admin.roles.store'))
            }
        },

        syncWithPermissions(e) {
          let value = +e.target.value
          e.target.checked ? this.form.permissions.push(value) : this.form.permissions.splice(this.form.permissions.indexOf(value), 1)
          this.form.permissions = [...new Set(this.form.permissions)]
        },
    }
}
</script>

<style scoped>

</style>
