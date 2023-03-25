<script setup>
import {Link} from '@inertiajs/vue3'
import { TailwindPagination } from 'laravel-vue-pagination';

import { ref } from 'vue';

const usersData = ref({});

const getResults = async (page = 1) => {
    const response = await fetch(`/admin/list-users?page=${page}`);
    usersData.value = await response.json();
}

getResults();
</script>

<template>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg overflow-auto">
        <!-- Table of users from props -->
        <table class="table-auto mx-auto w-full">
            <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in usersData.data" :key="user.id">
                <td class="border px-4 py-2 text-center">{{ user.id }}</td>
                <td class="border px-4 py-2">{{ user.name }}</td>
                <td class="border px-4 py-2">{{ user.email }}</td>
                <td class="border px-4 py-2 text-center">{{
                        user.status === 1 ? "Active" : user.status === 0 ? "Inactive" : "Null"
                    }}
                </td>
                <td class="border px-4 py-2 text-center">{{ user.roles[0].name }}</td>
                <td class="border px-4 py-2">
                    <div class="flex justify-evenly">
                        <Link href="#" title="Edit user">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4">
                                <path
                                    d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z"/>
                            </svg>
                        </Link>
                        <Link href="#" title="Disable user">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-5">
                                <path
                                    d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L353.3 251.6C407.9 237 448 187.2 448 128C448 57.3 390.7 0 320 0C250.2 0 193.5 55.8 192 125.2L38.8 5.1zM264.3 304.3C170.5 309.4 96 387.2 96 482.3c0 16.4 13.3 29.7 29.7 29.7H514.3c3.9 0 7.6-.7 11-2.1l-261-205.6z"/>
                            </svg>
                        </Link>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="flex justify-center mt-3">
            <TailwindPagination :data="usersData" @pagination-change-page="getResults" :limit="1" :keepLength="true"/>
        </div>
    </div>
</template>

