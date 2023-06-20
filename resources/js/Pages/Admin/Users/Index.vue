<script setup>
import {usePage, Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import UsersTable from "@/Components/Tables/UsersTable.vue";

const {users} = usePage().props

</script>

<template>
    <Head>
        <title>User Administration</title>
    </Head>
    <MainLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Administration</h2>
        </template>

        <div class="max-w-[100rem] mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
            <UsersTable :users="users" />
        </div>
    </MainLayout>
</template>
