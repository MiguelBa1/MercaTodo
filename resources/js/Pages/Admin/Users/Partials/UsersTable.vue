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
    const response = await fetch(route('admin.api.users.index', {page: page}));
    usersData.value = await response.json();
    pageNumber.value = page;
}

getUsers();

const manageUserStatus = async (id, name) => {
    $toast.clear();
    const response = await axios.patch(route('admin.api.users.status.update', id));
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
                        <div class="grid grid-rows-2 gap-1">
                            <Link :href="route('admin.edit.user', user.id)" title="Edit user"
                                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded text-center"
                            >
                                Edit
                            </Link>
                            <button v-if="user.status === 'Active'"
                                    @click="manageUserStatus(user.id, user.name)"
                                    title="Disable user"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded text-center"
                            >
                                Disable
                            </button>
                            <button v-if="user.status === 'Inactive'"
                                    @click="manageUserStatus(user.id, user.name)"
                                    title="Enable user"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-2 rounded text-center"
                            >
                                Enable
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

