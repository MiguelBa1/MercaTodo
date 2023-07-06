<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownButton from "@/Components/DropdownButton.vue";
import {onUnmounted, ref} from "vue";
import axios from "axios";
import {useToast} from "vue-toast-notification";

const toast = useToast();

const {products} = defineProps({
    products: {
        type: Object,
        required: true,
    }
});

let loading = ref(false);
let downloadLink = ref(null);
let pollingInterval = null;
let isPolling = ref(false);

const errorMessage = ref('');

const exportProducts = async (from = 1, to = products.total) => {
    if (loading.value) {
        toast.info('Export already in progress');
        return;
    }
    downloadLink.value = null;
    try {
        const response = await axios.get(route('admin.api.products.export', {
            from: from,
            to: to,
        }));
        startPolling(response.data.filename);
    } catch (error) {
        errorMessage.value = error.response.data.message;
        loading.value = false;
        toast.error('Something went wrong');
    }
}

const startPolling = (filename) => {
    loading.value = true;
    pollingInterval = setInterval(() => checkExport(filename), 3000);
}

const checkExport = async (filename) => {
    try {
        isPolling.value = true;
        const response = await axios.get(route('admin.api.products.export.check', {
            fileName: filename,
        }));
        if (response.status === 200 && response.data.status === 'READY') {
            loading.value = false;
            downloadLink.value = route('admin.api.products.export.download', {
                fileName: filename,
            });
            clearInterval(pollingInterval);
            localStorage.removeItem('exportFilename');
            toast.success('Export ready');
            isPolling.value = false;
        }
    } catch (error) {
        errorMessage.value = error.response.data.message;
        toast.error('Something went wrong');
    }
}

const exportCurrentPageProducts = () => exportProducts(products.from, products.to);

const exportAllProducts = () => exportProducts();

onUnmounted(() => {
    clearInterval(pollingInterval);
});
</script>

<template>
    <Dropdown>
        <template #trigger>
            <button
                class="inline-flex w-full items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 focus:outline-none text-white rounded shadow">
                <svg class="text-white h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12l7 7 7-7"/>
                </svg>
                Export Products
            </button>
        </template>

        <template #content>
            <DropdownButton :action="exportCurrentPageProducts">
                Export current page
            </DropdownButton>
            <DropdownButton :action="exportAllProducts">
                Export all products
            </DropdownButton>
        </template>
    </Dropdown>
    <div v-if="loading" class="flex items-center">
        <div class="dots-loading flex items-center">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <p class="ml-2 inline-block align-middle">Generating</p>
    </div>
    <a v-if="downloadLink && !errorMessage" :href="downloadLink" class="flex items-center underline">
        Download Export
    </a>
    <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
</template>

<style>
.dots-loading {
    position: relative;
    width: 30px;
    height: 20px;
}

.dots-loading div {
    display: inline-block;
    width: 6px;
    height: 6px;
    margin: 0 2px;
    background: #3490dc;
    border-radius: 50%;
    animation: dots-loading 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
}

.dots-loading div:nth-child(1) {
    animation-delay: -0.24s;
}

.dots-loading div:nth-child(2) {
    animation-delay: -0.12s;
}

.dots-loading div:nth-child(3) {
    animation-delay: 0s;
}

@keyframes dots-loading {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.5);
    }
}
</style>
