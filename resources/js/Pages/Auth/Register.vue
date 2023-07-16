<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import {ref} from "vue";

const {document_types, departments} = usePage().props;

const form = useForm({
    name: '',
    surname: '',
    email: '',
    password: '',
    password_confirmation: '',
    document: '',
    document_type: '',
    phone: '',
    address: '',
    city_id: '',
    terms: false,
});

const department_id = ref('');
const cities = ref({});
const getCities = async () => {
    const response = await fetch(route('api.cities.index', department_id.value));
    cities.value = await response.json();
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head>
            <title>Register</title>
        </Head>

        <form @submit.prevent="submit">
            <div class="sm:grid sm:grid-cols-2 gap-2">
                <div>
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

                <div>
                    <InputLabel for="surname" value="Surname"/>

                    <TextInput
                        id="surname"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.surname"
                        required
                        autocomplete="surname"
                    />

                    <InputError class="mt-2" :message="form.errors.surname"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="email" value="Email"/>

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="password" value="Password"/>

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="password_confirmation" value="Confirm Password"/>

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="document" value="Document"/>

                    <TextInput
                        id="document"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.document"
                        required
                        autocomplete="document"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    />

                    <InputError class="mt-2" :message="form.errors.document"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="document_type" value="Document Type"/>

                    <select
                        id="document_type"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.document_type"
                        required
                        autocomplete="document_type"
                    >
                        <option value="" disabled selected>Select a type</option>
                        <option v-for="document_type in document_types" :value="document_type">
                            {{ document_type }}
                        </option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.document_type"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="phone" value="Phone"/>

                    <TextInput
                        id="phone"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        required
                        autocomplete="phone"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    />

                    <InputError class="mt-2" :message="form.errors.phone"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="address" value="Address"/>

                    <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        required
                        autocomplete="address"
                    />

                    <InputError class="mt-2" :message="form.errors.address"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="departments" value="Department"/>

                    <select
                        id="departments"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="department_id"
                        required
                        autocomplete="department_id"
                        @change="getCities"
                    >
                        <option value="" disabled selected>Select a department</option>
                        <option v-for="department in departments" :value="department.id" :key="department.id">
                            {{ department.name }}
                        </option>
                    </select>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="cities" value="City"/>

                    <select
                        id="cities"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.city_id"
                        required
                        autocomplete="city_id"
                    >
                        <option value="" disabled selected>Select a city</option>
                        <option v-for="city in cities" :value="city.id" :key="city.id">
                            {{ city.name.charAt(0).toUpperCase() + city.name.toLowerCase().slice(1) }}
                        </option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.city_id"/>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
