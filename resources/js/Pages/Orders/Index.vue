<script setup lang="ts">
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from '@inertiajs/vue3';
import {Ref, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import OrderItem from "@/Pages/Orders/Partials/OrderItem.vue";
import {Order} from "@/types";

const orders: Ref<Order[]> = ref(usePage().props.orders);
</script>

<template>
    <MainLayout>
        <Head>
            <title>Orders</title>
        </Head>

        <template #header>
            <h2>My orders</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <OrderItem v-if="orders.length > 0" v-for="order in orders" :key="order.id" :order="order" />
            <div v-else class="bg-white flex flex-col justify-center py-12">
                <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl m-4">
                    <div class="md:flex p-8">
                        <!-- Pending SVG icon -->
                        <div class="md:flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 class="h-24 w-24 text-yellow-500 m-4">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                <line x1="12" y1="6" x2="12" y2="12" stroke="currentColor" stroke-width="2" />
                                <circle cx="12" cy="16" r="1" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="p-8">
                            <h3 class="text-xl leading-6 font-medium text-gray-900">
                                No orders yet
                            </h3>
                            <div class="mt-2">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                        Order reference
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            You haven't placed any orders yet.
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
