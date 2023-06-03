<script setup lang="ts">
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from '@inertiajs/vue3';
import {onMounted, Ref, ref} from "vue";
import axios from "axios";

interface OrderDetail {
    id: number;
    product_id: number;
    order_id: number;
    product_name: string;
    product_price: string;
    quantity: number;
}

interface Order {
    id: number;
    reference: string;
    status: string;
    total: string;
    created_at: string;
    order_details: OrderDetail[];
}

const orders: Ref<Order[]> = ref([]);

onMounted(async () => {
    const response = await axios.get(route('api.order.index'));
    orders.value = response.data.orders;
});

</script>

<template>
    <MainLayout>
        <Head>
            <title>Orders</title>
        </Head>

        <template #header>
            <h2>My orders</h2>
        </template>

        <!-- Orders -->
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div v-for="order in orders" :key="order.id" class="bg-white shadow overflow-hidden sm:rounded-md mb-4">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Order #{{ order.reference }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Created at {{ order.created_at }}
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Status
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ order.status }}
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
                                            <!-- Heroicon name: solid/cart -->
                                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M1 2a1 1 0 011-1h2.782a1 1 0 01.978.727l.5 2a1 1 0 01-.227.993L4.68 6H16a1 1 0 010 2H4.468l1.446 6.545A1 1 0 016 16h10a1 1 0 110 2H6a1 1 0 01-.995-.9L3.28 4H2a1 1 0 01-1-1zm3.82 4H16a1 1 0 01.995 1.1l-1.5 7A1 1 0 0114.5 14H6.32l1.1-5z" clip-rule="evenodd" />
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
                    </dl>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
