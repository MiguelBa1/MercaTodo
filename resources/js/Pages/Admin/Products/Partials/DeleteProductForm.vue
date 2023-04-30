<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import { useToast } from "vue-toast-notification";

const $toast = useToast()

const { product } = defineProps({
    product: Object
})
const deleteProduct = () => {
    axios(route('admin.api.products.destroy', product.id), {
        method: 'DELETE'
    }).then(response => {
        $toast.success('Product deleted successfully!')
        setTimeout(() => {
            window.location.href = route('admin.view.products')
        }, 1000)
    }).catch(error => {
        $toast.error('Something went wrong!')
    })
};

</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Product</h2>

            <p class="mt-1 text-sm text-gray-600">
                Once this product is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <DangerButton @click="deleteProduct">Delete Product</DangerButton>
    </section>
</template>

