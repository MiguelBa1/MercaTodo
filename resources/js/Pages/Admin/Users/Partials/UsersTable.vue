<script setup>
import {Link} from '@inertiajs/vue3'
import {TailwindPagination} from 'laravel-vue-pagination';
import {useToast} from "vue-toast-notification";
import axios from "axios";
import {ref} from 'vue';

const $toast = useToast();
const usersData = ref({});
const pageNumber = ref(1);

const getUsers = async (page = 1) => {
    const response = await fetch(route('admin.api.list.users', {page: page}));
    usersData.value = await response.json();
    pageNumber.value = page;
}

getUsers();

const manageUserStatus = async (id, name) => {
    $toast.clear();
    const response = await axios.patch(route('admin.api.update.user.status', id));
    if (response.status === 200) {
        await getUsers(pageNumber.value);
        $toast.success(`${name} status has been updated successfully`);
    } else {
        $toast.error(`Something went wrong, please try again later`);
    }
}

</script>

<template>
    <div class="p-4 sm:p-6 shadow sm:rounded-lg">
        <div class="overflow-auto">
            <!-- Table of users from props -->
            <table class="table-auto mx-auto w-full border">
                <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Document</th>
                    <th class="px-4 py-2 border">Document Type</th>
                    <th class="px-4 py-2 border">City</th>
                    <th class="px-4 py-2 border">Address</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Role</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in usersData.data" :key="user.id">
                    <td class="border px-4 py-2 text-center">{{ user.id }}</td>
                    <td class="border px-4 py-2">{{ user.name }}</td>
                    <td class="border px-4 py-2">{{ user.email }}</td>
                    <td class="border px-4 py-2">{{ user.document }}</td>
                    <td class="border px-4 py-2">{{ user.document_type }}</td>
                    <td class="border px-4 py-2">{{ user.city_name }}</td>
                    <td class="border px-4 py-2">{{ user.address }}</td>
                    <td class="border px-4 py-2 text-center">{{ user.status }}</td>
                    <td class="border px-4 py-2 text-center">{{ user.role_name }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex justify-evenly">
                            <Link :href="route('admin.edit.user', user.id)" title="Edit user">
                                <svg class="h-6 w-6 text-blue-500"
                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </Link>
                            <!--Disable user button patch to /admin/disable-user/{id}-->
                            <button v-if="user.status === 'Active'" @click="manageUserStatus(user.id, user.name)"
                                    title="Disable user">
                                <svg class="h-6 w-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="8.5" cy="7" r="4"/>
                                    <line x1="18" y1="8" x2="23" y2="13"/>
                                    <line x1="23" y1="8" x2="18" y2="13"/>
                                </svg>
                            </button>
                            <button v-if="user.status === 'Inactive'" @click="manageUserStatus(user.id, user.name)"
                                    title="Enable user">
                                <svg class="h-6 w-6 text-green-500" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor"
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
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-3">
            <TailwindPagination :data="usersData" @pagination-change-page="getUsers" :limit="1" :keepLength="true"/>
        </div>
    </div>
</template>

