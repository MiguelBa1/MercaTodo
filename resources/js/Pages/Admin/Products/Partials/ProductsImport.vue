<script setup>
import {onUnmounted, ref} from "vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";

const modalOpen = ref(false);

const errorForm = ref('');
const errorRows = ref({});
const operationResult = ref('');
let importFile = null;

let loading = ref(false);
let pollingInterval = null;
let isPolling = ref(false);

const openModal = () => {
    modalOpen.value = true;
}

const closeModal = () => {
    modalOpen.value = false;
}

const onFileChange = (e) => {
    importFile = e.target.files[0];
}

const submit = async () => {
    errorForm.value = '';
    errorRows.value = {};
    operationResult.value = '';

    if (loading.value) {
        errorForm.value = 'Import already in progress';
        return;
    }
    if (!importFile) {
        errorForm.value = 'Please select a file';
        return;
    }
    const formData = new FormData();
    formData.append('file', importFile);
    try {
        await axios.post(route('api.admin.products.import'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        startPolling(importFile.name);
    } catch (error) {
        if (error.response.status === 422) {
            errorForm.value = error.response.data.errors.file[0];
        } else {
            errorForm.value = "Something went wrong. Please try again later.";
        }
    }
}

const startPolling = (filename) => {
    loading.value = true;
    isPolling.value = true;
    pollingInterval = setInterval(() => checkImport(filename), 3000);
}

const checkImport = async (filename) => {
    try {
        const response = await axios.get(route('api.admin.products.import.check', {
            fileName: filename,
        }));

        if (response.data.status === 'HAS_ERRORS') {
            operationResult.value = "The import has been done, but there are some errors. Please check the error rows and fix them, then reload the file and import again.";
            errorRows.value = response.data.errors;
            endPolling();
        }

        if (response.status === 200 && response.data.status === 'READY') {
            operationResult.value = "The import has been done successfully.";
            endPolling();
        }
    } catch (error) {
        endPolling();
    }
}

const endPolling = () => {
    clearInterval(pollingInterval);
    loading.value = false;
    isPolling.value = false;
}

onUnmounted(endPolling);
</script>

<template>
    <button
        class="flex w-full sm:w-auto items-center px-4 py-2 bg-green-500 hover:bg-green-600 font-bold focus:outline-none text-white rounded"
        @click="openModal"
    >
        <svg class="text-white h-6 w-6 transform rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
        Import Products
    </button>
    <Modal :show="modalOpen" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-semibold">
                Import Products from a CSV File
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                The CSV file must contain the following columns: SKU, Name, Description, Price, and Quantity.
            </p>
            <p class="mt-1 text-sm text-gray-600">
                The same format as the one used in the export file.
            </p>
            <p class="mt-1 text-sm text-gray-600">
                Please note that if an error is found in any row, it will be reported afterwards, but valid rows will still be processed.
            </p>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <label class="block mb-2 text-sm" for="file_input">Upload file</label>
                    <input
                        class="file:p-2 file:border-none block w-full text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none "
                        id="file_input" type="file"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        @change="onFileChange"
                    >
                    <InputError class="mt-2" :message="errorForm"/>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="submit"
                            :disabled="loading || isPolling"
                            class="inline-flex justify-center py-1 px-4 border border-transparent
                             shadow-sm font-medium rounded-md text-white
                             bg-green-500 hover:bg-green-600 disabled:bg-green-600 disabled:cursor-not-allowed
                             focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Import
                    </button>

                    <div v-if="loading" class="flex items-center">
                        <div class="dots-loading flex items-center">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <p class="ml-2 inline-block align-middle">Importing</p>
                    </div>
                </div>
            </form>

            <div v-if="operationResult" class="mt-4">
                <h3 class="text-lg font-semibold">
                    {{ operationResult }}
                </h3>
            </div>

            <div v-if="errorRows && Object.keys(errorRows).length > 0"
                 class="mt-4 overflow-auto max-h-72 bg-gray-100 p-2">
                <p class="mt-1 text-md text-black">
                    The following rows have errors. Please note that valid rows have already been processed:
                </p>
                <ul>
                    <li v-for="(errors, rowIndex) in errorRows" :key="rowIndex" class="mt-2 text-red-500">
                        <strong>Row {{ rowIndex }}:</strong>
                        <ul>
                            <li v-for="(error, index) in errors" :key="index" class="ml-4">
                                - {{ error }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </Modal>
</template>
