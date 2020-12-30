<template>
    <div class="flex flex-col items-center my-12">
        <div class="flex text-gray-700">

            <!--Previous page button-->
            <inertia-link
                v-if="previousPageUrl && prevPage"
                :href="previousPageUrl"
                preserve-scroll
                class="h-8 w-8 mr-1 flex justify-center items-center  cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-chevron-left w-4 h-4">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </inertia-link>

            <!--Pages-->
            <div class="flex h-8 font-medium">
                <inertia-link
                    v-for="page in pagination"
                    v-if="page.visible"
                    class="w-8 md:flex justify-center items-center hidden  cursor-pointer leading-5 transition duration-150 ease-in  border-t-2"
                    preserve-scroll
                    :href="page.url"
                    :key="page.key"
                    :class="{'border-orange-600' : currentPage === page.key}"
                >
                    {{ page.text }}
                </inertia-link>
            </div>

            <!--Next page button-->
            <inertia-link
                v-if="nextPageUrl && nextPage"
                :href="nextPageUrl"
                preserve-scroll
                class="h-8 w-8 ml-1 flex justify-center items-center  cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-chevron-right w-4 h-4">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </inertia-link>

        </div>
    </div>
</template>

<script>
export default {
    name: "pagination",

    props: {
        data: {
            type: Object,
            required: true
        },
        prevPage: {
            type: Boolean,
            default: true
        },
        nextPage: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            middle: 1
        }
    },

    methods: {
        nextUrl(page) {
            return `${this.data.path}/?page=${page}`
        }
    },

    computed: {
        currentPage() {
            return this.data.current_page
        },

        lastPage() {
            return this.data.last_page
        },

        previousPageUrl() {
            return this.data.prev_page_url
        },

        nextPageUrl() {
            return this.data.next_page_url
        },

        pagination() {
            let pagination = []

            for (let i = 1; i <= this.lastPage; i++) {
                if (this.data.current_page === i) {
                    pagination.push({
                        url: '#',
                        text: i,
                        key: i,
                        visible: this.currentPage === i
                    })
                } else {
                    pagination.push({
                        url: this.nextUrl(i),
                        text: i,
                        key: i,
                        visible:
                            i === (this.currentPage - this.middle)
                            || i === (this.currentPage + this.middle)
                            || i === 1
                            || i === this.lastPage
                    })

                    if (i === (this.currentPage - this.middle) && i !== 1) {
                        pagination.splice(i - this.middle, 0, {
                            url: '#',
                            text: '...',
                            key: this.lastPage + 1,
                            visible: true
                        })
                    }

                    if (i === (this.currentPage + this.middle) && i !== this.lastPage) {
                        pagination.splice(i + this.middle, 0, {
                            url: '#',
                            text: '...',
                            key: this.lastPage + 2,
                            visible: true
                        })
                    }
                }
            }

            return pagination
        }
    }
}
</script>

<style scoped>

</style>
