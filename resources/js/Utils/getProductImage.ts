export const getProductImage = (product_image: string) => {
    if (product_image) {
        return `/storage/images/${product_image}`;
    } else {
        return '/images/no-image.svg';
    }
}
