<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import { useToast } from "vue-toast-notification";

const $toast = useToast()

const { product } = defineProps({
    product: Object
})
const deleteProduct = () => {
    axios(route('api.admin.products.destroy', product.id), {
        method: 'DELETE'
    }).then(response => {
        $toast.success('Product deleted successfully!')
        setTimeout(() => {
            window.location.href = route('admin.products.index')
        }, 1000)
    }).catch(error => {
        if (error.response.status === 403) {
            $toast.error(`You don't have permission to perform this action`)
        } else {
            $toast.error(`Something went wrong, please try again later`)
        }
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

