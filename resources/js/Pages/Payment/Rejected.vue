<script setup lang="ts">
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, usePage} from "@inertiajs/vue3";
import {Ref, ref} from "vue";
import {Order} from "@/types";

defineOptions({
    layout: MainLayout,
})

const page = usePage();

const order: Ref<Order> = ref(page.props.order);

</script>

<template>
    <Head>
        <title>Payment Rejected</title>
    </Head>
    <div class="bg-white flex flex-col justify-center py-12">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl m-4">
            <div class="md:flex p-8">
                <!-- Rejected SVG icon -->
                <div class="md:flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         class="h-24 w-24 text-red-500 m-4">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l10 10M7 17L17 7" />
                    </svg>
                </div>
                <div class="p-8">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">
                        Payment Rejected
                    </h3>
                    <div class="mt-2">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Order reference
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ order.reference }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Total
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    $ {{ order.total }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Created at
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ order.created_at }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-4">
                        <Link href="/" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Go back to home
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

