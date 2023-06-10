<script setup lang="ts">
import {defineProps} from 'vue';
import OrderDetailItem from "./OrderDetailItem.vue";
import {Order} from "@/types";

const props = defineProps({
    order: Object as () => Order,
});

const retryPayment = () => {
    location.href = props.order.process_url;
}
</script>

<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mb-4">
        <div class="px-4 py-5 sm:px-6 flex justify-between">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Order #{{ order.reference }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Created at {{ order.created_at }}
                </p>
            </div>
            <div v-if="order.status === 'PENDING'">
                <button @click="retryPayment" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none">
                    Retry Payment
                </button>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <OrderDetailItem :order="order"/>
            </dl>
        </div>
    </div>
</template>
