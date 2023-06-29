<script setup lang="ts">
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, usePage} from "@inertiajs/vue3";
import {Ref, ref} from "vue";
import {Order} from "@/types";
import CompletedIcon from "@/Components/Icons/CompletedIcon.vue";
import RejectedIcon from "@/Components/Icons/RejectedIcon.vue";
import PendingIcon from "@/Components/Icons/PendingIcon.vue";

defineOptions({
    layout: MainLayout,
    components: {
        CompletedIcon,
        RejectedIcon,
        PendingIcon,
    }
})

const page = usePage();
const order: Ref<Order> = ref(page.props.order);
const title: Ref<string> = ref("");

switch (order.value.status) {
    case "COMPLETED":
        title.value = "Payment Successful";
        break;
    case "REJECTED":
        title.value = "Payment Rejected";
        break;
    default:
        title.value = "Payment Pending";
        break;
}
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>
    <div class="bg-white flex flex-col justify-center py-12">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl m-4">
            <div class="md:flex p-8">
                <div class="md:flex-shrink-0 flex items-center justify-center">
                    <CompletedIcon v-if="order.status === 'COMPLETED'" />
                    <RejectedIcon v-else-if="order.status === 'REJECTED'" />
                    <PendingIcon v-else />
                </div>
                <div class="p-8">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">
                        {{ title }}
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
