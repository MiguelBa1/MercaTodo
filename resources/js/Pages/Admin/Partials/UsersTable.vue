<script setup>
import {Link} from '@inertiajs/vue3'
import {TailwindPagination} from 'laravel-vue-pagination';
import axios from "axios";
import {ref} from 'vue';

const usersData = ref({});
const pageNumber = ref(1);

const getUsers = async (page = 1) => {
    const response = await fetch(`/admin/list-users?page=${page}`);
    usersData.value = await response.json();
    pageNumber.value = page;
}

getUsers();

const manageUserStatus = async (id, name) => {
    const response = await axios.patch(`/admin/manage-user-status/${id}`)
    if (response.status === 200) {
        await getUsers(pageNumber.value);
    }
}

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
                        <!--Disable user button patch to /admin/disable-user/{id}-->
                        <!--if user.status === 1-->
                        <button v-if="user.status === 1"  @click="manageUserStatus(user.id, user.name)" title="Disable user">
                            <svg class="h-6 w-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="18" y1="8" x2="23" y2="13"/>
                                <line x1="23" y1="8" x2="18" y2="13"/>
                            </svg>
                        </button>
                        <!--                        else if user.status === 0-->
                        <button v-if="user.status === 0"  @click="manageUserStatus(user.id, user.name)" title="Enable user">
                            <svg class="h-6 w-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="flex justify-center mt-3">
            <TailwindPagination :data="usersData" @pagination-change-page="getUsers" :limit="1" :keepLength="true"/>
        </div>
    </div>
</template>

