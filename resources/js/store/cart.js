import {defineStore} from "pinia";
import axios from "axios";

export const useCartStore = defineStore({
    id: 'mainStore',
    state: () => ({
        cart: [],
        isCartSynced: false,
    }),
    getters: {
        cartItemsCount() {
            return Object.keys(this.cart).length;
        }
    },
    actions: {
        async addToCart(product_id, quantity = 1) {
            const response = await axios.post(route('api.cart.store'), {product_id, quantity});
            await this.syncCart(true);
            return response;
        },
        async syncCart(forceSync = false) {
            if (!this.isCartSynced || forceSync) {
                const {data} = await axios.get(route('api.cart.index'));
                this.cart = data;
                this.isCartSynced = true;
            }
        },
        async removeFromCart(product_id) {
            const response = await axios.delete(route('api.cart.destroy', product_id));
            await this.syncCart(true);
            return response;
        }
    },
})
