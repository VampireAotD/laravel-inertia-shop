export default {
    methods: {
        refreshImagesArray(images) {
            this.product.images = images
        },

        clearImagesFromUploaded() {
            this.product.images.map((image, index) => {
                return image.path ?? this.product.images.splice(index)
            })
        }
    }
}
