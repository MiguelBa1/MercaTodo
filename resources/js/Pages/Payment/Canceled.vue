<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from "@inertiajs/vue3";
import {usePage} from "@inertiajs/vue3";
import {Link} from "@inertiajs/vue3";

defineOptions({
    layout: MainLayout,
})

const page = usePage();
</script>

<template>
    <Head>
        <title>Payment Canceled</title>
    </Head>
    <div class="bg-white flex flex-col justify-center py-12">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl m-4">
            <!-- Canceled SVG icon -->
            <div class="md:flex">
                <div class="md:flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         class="h-24 w-24 text-red-500 m-4">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l10 10M7 17L17 7" />
                    </svg>
                </div>
                <div class="p-8">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">
                        Payment Canceled
                    </h3>
                    <!-- Message of order canceled -->
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Your order has been canceled. If you have any questions, please contact our customer service.
                        </p>
                    </div>
                    <!-- buttons -->
                    <div class="mt-4">
                        <Link href="/" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Go back to home
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
