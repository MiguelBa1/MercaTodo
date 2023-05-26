import {defineStore} from "pinia";
import axios from "axios";

export const useCartStore = defineStore({
    id: 'mainStore',
    state: () => ({
        cart: [],
    }),
    getters: {
        cartItemsCount() {
            return Object.keys(this.cart).length;
        }
    },
    actions: {
        async addToCart(product_id, quantity = 1) {
            const response = await axios.post(route('api.cart.addProduct'), {product_id, quantity});
            return response;
        },
        async syncCart() {
            const {data} = await axios.get(route('api.cart.getProducts'));
            this.cart = Object.entries(data).map(([id, quantity]) => ({
                id: parseInt(id),
                quantity: parseInt(quantity),
            }));
        },
        async removeFromCart(product_id) {
            const response = await axios.delete(route('api.cart.removeProduct', product_id));
            return response;
        }
    },
})
