<template>
    <div>
        <inertia-link :href="$route('change-language', {lang : 'en'})">en</inertia-link>
        <inertia-link :href="$route('change-language', {lang : 'ru'})">ru</inertia-link>
        <inertia-link :href="$route('change-language', {lang : 'ua'})">ua</inertia-link>
    </div>
</template>

<script>
export default {
    name: "LanguageSelection"
}
</script>

<style scoped>

</style>
