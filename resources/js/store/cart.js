import {defineStore} from "pinia";
import axios from "axios";

export const useCartStore = defineStore({
    id: 'mainStore',
    state: () => ({
        cart: {},
    }),
    getters: {
        cartItemsCount() {
            return Object.keys(this.cart).length;
        }
    },
    actions: {
        async addToCart(product_id, quantity = 1) {
            await axios.post(route('api.cart.addProduct'), {product_id, quantity});
            await this.syncCart();
        },
        async syncCart() {
            const {data} = await axios.get(route('api.cart.getProducts'));
            this.cart = data;
        },
        async removeFromCart(product) {
            await axios.delete(route('api.cart.removeProduct', product.id));
            await this.syncCart();
        },
        async clearCart() {
            await axios.delete(route('api.cart.clear'));
            await this.syncCart();
        },
        productInCart(product_id) {
            return this.cart.hasOwnProperty(product_id);
        }
    },
})
