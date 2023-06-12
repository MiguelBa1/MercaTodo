<script setup>
import NavBar from "@/Components/NavBar.vue";
import { useCartStore } from "@/store/cart";
import { onMounted } from "vue";
import {usePage} from "@inertiajs/vue3";

const store = useCartStore();
const { user } = usePage().props.auth;


onMounted(async () => {
    // if the user is logged in, fetch the cart from the server
    if (user) {
        await store.syncCart();
    }
});
</script>

<template>
    <NavBar/>
    <!-- Page Heading -->
    <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header"/>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <slot/>
    </main>
</template>
