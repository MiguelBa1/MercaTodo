<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useToast} from "vue-toast-notification";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const {brands, categories} = defineProps({
    brands: {
        type: Array,
        required: true
    },
    categories: {
        type: Array,
        required: true
    }
});

const $toast = useToast()

const imageUrl = ref(null)

const form = useForm(
    {
        sku: '',
        name: '',
        description: '',
        price: '',
        image: null,
        stock: '',
        brand_id: '',
        category_id: '',
    }
)

function onFileChange(event) {
    const file = event.target.files[0]
    if (file) {
        imageUrl.value = URL.createObjectURL(file)
        form.image = file
    }
}

const createProduct = () => {
    $toast.info('Creating product...');
    form.clearErrors()

    axios.postForm(route('api.admin.products.store'), form.data(), {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        $toast.success('Product created successfully!')

    }).catch(error => {
        form.setError(error.response.data.errors);
    })

}

</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Product Information</h2>
    </header>

    <form @submit.prevent="createProduct">
        <div class="sm:grid sm:grid-cols-2 gap-3">
            <div class="mt-4 sm:mt-0">
                <InputLabel for="sku" value="SKU"/>

                <TextInput
                    id="sku"
                    type="number"
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
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div class="flex flex-col mb-6">
                <InputLabel for="description" value="Description"/>
                <textarea id="description" v-model="form.description"
                          class="resize-none w-full px-3 mt-1 py-2 text-gray-700 border rounded-md focus:outline-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
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
                    autofocus
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
                    autofocus
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
                <select id="category_id" v-model="form.category_id"
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
            Create Product
        </PrimaryButton>

    </form>

</template>

