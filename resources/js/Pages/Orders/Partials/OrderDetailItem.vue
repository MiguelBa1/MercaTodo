<script setup lang="ts">
import {defineProps} from 'vue';
import {Order} from "@/types";

const props = defineProps({
    order: Object as () => Order,
});

const getStatusColor = (status: string) => {
    switch(status) {
        case 'PENDING':
            return 'bg-yellow-500';
        case 'REJECTED':
            return 'bg-red-600';
        case 'COMPLETED':
            return 'bg-green-600';
        default:
            return '';
    }
};

</script>

<template>
    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">
            Status
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <span :class="['text-white font-extrabold p-2 rounded', getStatusColor(order.status)]">
                {{ order.status }}
            </span>
        </dd>
    </div>
    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">
            Total
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            $ {{ order.total }}
        </dd>
    </div>
    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">
            Products
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li v-for="orderDetail in order.order_details" :key="orderDetail.id"
                    class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                    <div class="w-0 flex-1 flex items-center">
                        <svg class="h-6 w-6" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="ml-2 flex-1 w-0 truncate">
                            {{ orderDetail.product_name }}
                        </span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <span class="font-medium text-gray-900">
                            $ {{ orderDetail.product_price }}
                        </span>
                        <span class="ml-4 text-gray-700">
                            Quantity: {{ orderDetail.quantity }}
                        </span>
                    </div>
                </li>
            </ul>
        </dd>
    </div>
</template>
