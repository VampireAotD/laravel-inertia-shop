export default {
    methods : {
        refreshImagesArray(images){
            this.product.images = images
        },
        clearImagesFromUploaded(){
            this.product.images.map( (image, index) => {
                image.path ?? this.product.images.splice(index)
            })
        }
    }
}