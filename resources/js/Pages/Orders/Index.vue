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
            <OrderItem v-for="order in orders" :key="order.id" :order="order" />
        </div>
    </MainLayout>
</template>
