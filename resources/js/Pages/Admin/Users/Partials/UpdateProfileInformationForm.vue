<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import axios from "axios";
import { useToast } from "vue-toast-notification";
import {ref} from 'vue';

const $toast = useToast();

const user = usePage().props.user;

const form = useForm({
    name: user.name,
    email: user.email,
    role_name: user.role_name,
});

const updateProfileInformation = () => {
    $toast.info('Updating profile information...');

    axios.patch(route('admin.api.update.user.profile', user.id), form.data())
        .then(response => {
            $toast.success(response.data.message);
        })
        .catch(error => {
            form.setError(error.response.data.errors);
            $toast.error('There was an error updating your profile information.');
        });
};

const roles = ref([]);
const getRoles = async () => {
    await axios.get(route('admin.api.list.roles'))
        .then(response => {
            roles.value = response.data;
        });
};

getRoles();
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>
        <form @submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
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
                <InputLabel for="role" value="Role"/>

                <select
                    id="role"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    v-model="form.role_name"
                    required
                    autocomplete="role"
                >
                    <option v-for="role in roles" :value="role">
                        {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.roles"/>
            </div>

            <div>
                <InputLabel for="email" value="Email"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-gray-100 cursor-not-allowed"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :disabled="true"
                />

                <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
