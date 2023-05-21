<script setup lang="ts">
import {usePage, Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import UsersTable from "@/Components/Tables/UsersTable.vue";

defineProps({
    auth: {
        type: Object,
        required: true
    },
})

const {auth} = usePage().props

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
            <UsersTable />
        </div>
    </MainLayout>
</template>
