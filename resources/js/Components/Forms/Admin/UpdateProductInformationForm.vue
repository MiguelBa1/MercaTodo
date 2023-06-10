<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useToast} from "vue-toast-notification";
import {useForm} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const $toast = useToast()

const {product} = defineProps({
    product: Object
})

const imageUrl = ref(product.image ? `/storage/images/${product.image}` : null)

const form = useForm(
    {
        sku: product.sku,
        name: product.name,
        description: product.description,
        price: product.price,
        image: null,
        stock: product.stock.toString(),
        status: product.status,
        brand_id: product.brand_id,
        category_id: product.category_id,
    }
)

function onFileChange(event) {
    const file = event.target.files[0]
    if (file) {
        imageUrl.value = URL.createObjectURL(file)
        form.image = file
    }
}

const updateProduct = () => {
    $toast.info('Updating product information...')
    form.clearErrors()

    axios.postForm(route('admin.api.products.update', product.id), form.data(), {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        $toast.success('Product information updated successfully!')
    }).catch(error => {
        form.setError(error.response.data.errors);
        $toast.error('Something went wrong, please verify the information and try again.')
    })

}

const brands = ref([])
const categories = ref([])

const fetchBrands = async () => {
    const response = await axios.get(route('api.brands.index'))
    brands.value = response.data.brands
}

const fetchCategories = async () => {
    const response = await axios.get(route('api.categories.index'))
    categories.value = response.data.categories
}

onMounted(() => {
    fetchBrands();
    fetchCategories();
})
</script>
<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Product Information</h2>

        <p class="mt-1 text-sm text-gray-600">
            Update product information.
        </p>
    </header>

    <form @submit.prevent="updateProduct">
        <div class="sm:grid sm:grid-cols-2 gap-3">
            <div class="mt-4 sm:mt-0">
                <InputLabel for="sku" value="SKU"/>

                <TextInput
                    id="sku"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.sku"
                    required
                    autofocus
                    autocomplete="sku"
                />

                <InputError class="mt-2" :message="form.errors.sku"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="name" value="Name"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="description" value="Description"/>
                <textarea id="description" v-model="form.description"
                          class="resize-none w-full mt-1 px-3 py-2 text-gray-700 border rounded-md focus:outline-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </textarea>
                <InputError class="mt-2" :message="form.errors.description"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="price" value="Price"/>
                <TextInput
                    id="price"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.price"
                    required
                    autocomplete="price"
                />
                <InputError class="mt-2" :message="form.errors.price"/>
            </div>

            <div class="grid place-items-center md:grid-cols-2 mb-6">
                <div class="flex flex-col justify-center">
                    <InputLabel for="image" value="Image (800x800px)"/>
                    <input id="image" type="file" ref="image" @change="onFileChange"
                           class="w-full px-3 py-2 text-gray-700 border rounded-md focus:outline-none focus:border-indigo-500"
                           accept="image/jpeg, image/png, image/jpg"
                    >
                </div>
                <img v-if="imageUrl" :src="imageUrl" alt="product image" class="w-32 h-32 mt-2">

                <InputError class="mt-2" :message="form.errors.image"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="stock" value="Stock"/>

                <TextInput
                    id="stock"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.stock"
                    required
                    autocomplete="stock"
                />

                <InputError class="mt-2" :message="form.errors.stock"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="brand_id" value="Brand"/>

                <select id="brand_id" v-model="form.brand_id"
                        class="w-full px-3 py-2 text-gray-700 border rounded-md focus:outline-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.brand_id"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="category_id" value="Category"/>
                <select id="category_id" v-model="product.category_id"
                        class="w-full px-3 py-2 text-gray-700 border rounded-md focus:outline-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{
                            category.name
                        }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.category_id"/>
            </div>

        </div>

        <PrimaryButton type="submit" class="mt-4">
            Update Product
        </PrimaryButton>
    </form>
</template>

