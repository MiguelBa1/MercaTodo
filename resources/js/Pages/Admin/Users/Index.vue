<script setup lang="ts">
import {usePage} from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import UsersTable from "@/Pages/Admin/Users/Partials/UsersTable.vue";

defineProps({
    auth: {
        type: Object,
        required: true
    },
})

const {auth} = usePage().props

</script>

<template>
    <AuthenticatedLayout :auth="auth">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Administration</h2>
        </template>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
            <UsersTable />
        </div>
    </AuthenticatedLayout>
</template>
