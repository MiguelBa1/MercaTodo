<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from '@inertiajs/vue3';
import Card from "@/Pages/Admin/Partials/Card.vue";</script>

<template>
    <Head title="Administrator"/>
    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Administrator</h2>
        </template>


        <div
            class="max-w-7xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6 mx-2 sm:mx-4 md:mx-10 lg:mx-14 xl:mx-auto">
            <Card title="Manage Users" sub-title="View and manage users in the system."
                  :href="route('admin.view.users')">
                <svg class="h-8 w-8 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </Card>

            <Card title="Manage Products" sub-title="View and manage the products" :href="route('admin.view.products')">
                <svg class="h-8 w-8 text-black" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                     stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z"/>
                    <line x1="3" y1="21" x2="21" y2="21"/>
                    <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"/>
                    <path d="M5 21v-10.15"/>
                    <path d="M19 21v-10.15"/>
                    <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"/>
                </svg>
            </Card>
        </div>


    </MainLayout>
</template>
