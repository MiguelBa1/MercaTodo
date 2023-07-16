<script setup>
import {Head, useForm, usePage, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";

const { lastReport } = usePage().props;

const modalOpen = ref(false);
const modalMsg = ref('');

const openModal = () => {
    modalOpen.value = true;
}

const closeModal = () => {
    modalOpen.value = false;
}

const currentDate = new Date();
const oneYearAgoDate = new Date();
oneYearAgoDate.setFullYear(currentDate.getFullYear() - 1);

const form = useForm({
    start_date: oneYearAgoDate.toISOString().split("T")[0],
    end_date: currentDate.toISOString().split("T")[0],
});

const submit = async () => {
    try {
        const response = await axios.post(route('api.admin.report.generate'), form.data());
        if (response.status === 200) {
            modalMsg.value = response.data.message;
            openModal();
            form.reset();
        }
    } catch (error) {
        if (error.response.status === 422) {
            form.errors = error.response.data.errors[0];
        } else {
            modalMsg.value = "Something went wrong. Please try again later.";
            openModal();
        }
    }
}</script>

<template>
    <MainLayout>
        <Head>
            <title>Reports</title>
        </Head>

        <div class="grid gap-3 p-6 lg:p-12 max-w-7xl mx-auto mt-7 shadow rounded-lg">

            <!-- Button to redirect to the last report -->
            <div class="flex flex-col sm:flex-row justify-between">
                <h1 class="text-xl font-bold">Generate Reports</h1>
                <Link v-if="lastReport"  :href="route('admin.view.report', lastReport.id)">
                    <PrimaryButton>View Last Report</PrimaryButton>
                </Link>
            </div>

            <div class="my-5">
                <p>
                    You are about to generate a report, which will correspond to the entered date range.
                    The report includes information about the following:
                </p>
                <ul class="list-disc pl-5">
                    <li>Total Sales</li>
                    <li>Total Orders</li>
                    <li>Most Sold Product</li>
                    <li>Products Sold Per Category</li>
                    <li>Sales By Month</li>
                    <li>Top Selling Products</li>
                </ul>
            </div>

            <div>
                <form @submit.prevent="submit" class="flex flex-col md:flex-row justify-between">
                    <div>
                        <InputLabel class="mb-3" for="from" value="From"/>
                        <input
                            id="from"
                            v-model="form.start_date"
                            class="rounded-sm border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="from"
                            type="date"
                        >
                        <InputError :message="form.errors.start_date" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel class="mb-3" for="to" value="To"/>
                        <input
                            id="to"
                            v-model="form.end_date"
                            class="rounded-sm border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="to"
                            type="date"
                        >
                        <InputError :message="form.errors.end_date" class="mt-2"/>
                    </div>
                    <div>
                        <PrimaryButton class="mt-5" type="submit">Generate</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
        <Modal :show="modalOpen" @close="closeModal">
            <div class="p-6 relative">
                <button @click="closeModal" class="absolute right-0 top-0 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-gray-600 hover:text-gray-900">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold mb-2">Report Generation in Process</h2>
                <p class="text-sm text-gray-600">Your report is being generated. Any updates on the status of this process will be sent to your email.</p>
            </div>
        </Modal>
    </MainLayout>
</template>
