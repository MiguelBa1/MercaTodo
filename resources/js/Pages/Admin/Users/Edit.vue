<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import UpdatePasswordForm from '../../../Components/Forms/Admin/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '../../../Components/Forms/Admin/UpdateProfileInformationForm.vue';
import {Head, usePage} from '@inertiajs/vue3';

const user = usePage().props.user;

</script>

<template>
    <Head>
        <title>
            Edit user: {{ user.name }}
        </title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit user: {{ user.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm/>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl"/>
                </div>

            </div>
        </div>
    </MainLayout>
</template>
