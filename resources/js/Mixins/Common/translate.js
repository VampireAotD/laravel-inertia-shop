export default {
    methods: {
        __(key, replacement) {
            let translation;

            try {
                translation = key.split('.').reduce((obj, key) => obj[key] || null, this.$page.translations[this.$page.locale])
            } catch (e) {
                translation = key
            }

            _.forEach(replacement, (value, key) => {
                translation = translation.replace(key, value)
            })

            return translation
        }
    }
}
