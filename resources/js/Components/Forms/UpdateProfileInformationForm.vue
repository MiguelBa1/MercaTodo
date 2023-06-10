<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useToast} from "vue-toast-notification";
import {Link, useForm, usePage} from '@inertiajs/vue3';
import {ref} from "vue";

const $toast = useToast();

const props = defineProps(
    {
        mustVerifyEmail: Boolean,
        status: String,
        departments: {
            type: Object
        }
    }
);

const { user, departments } = usePage().props;

const department_id = ref(user.department_id);
const cities = ref({});

const getCities = async () => {
    const response = await fetch(route('api.list.cities', department_id.value));
    cities.value = await response.json();
};

getCities();

const form = useForm({
    name: user.name,
    surname: user.surname,
    email: user.email,
    department_id: user.department_id,
    city_id: user.city_id,
    phone: user.phone.toString(),
    address: user.address,
    document: user.document.toString(),
    document_type: user.document_type,
});


const updateProfileInformation = () => {
    $toast.info('Updating profile information...');
    form.clearErrors();

    form.patch(route('profile.update'), {
        onSuccess: () => {
            $toast.success('Profile information updated successfully.');
        },
        onError: (error) => {
            form.setError(error.response.data.errors);
            $toast.error('There was an error updating your profile information.');
        }
    });
};

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
            <div class="sm:grid sm:grid-cols-2 sm:gap-3">
                <div class="mt-4 sm:mt-0">
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

                <div class="mt-4 sm:mt-0">
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
                    <InputLabel for="departments" value="Department"/>

                    <select
                        id="departments"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="department_id"
                        required
                        autocomplete="department_id"
                        @change="getCities"
                    >
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
                        <option v-for="city in cities" :value="city.id" :key="city.id">
                            {{ city.name }}
                        </option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.city_id"/>
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
                    <InputLabel for="document" value="Document"/>

                    <TextInput
                        id="document"
                        type="text"
                        class="mt-1 block w-full bg-gray-100 cursor-not-allowed"
                        v-model="form.document"
                        required
                        autocomplete="username"
                        :disabled="true"
                    />

                    <InputError class="mt-2" :message="form.errors.document"/>
                </div>

                <div class="mt-4 sm:mt-0">
                    <InputLabel for="document_type" value="Document Type"/>

                    <TextInput
                        id="document_type"
                        type="text"
                        class="mt-1 block w-full bg-gray-100 cursor-not-allowed"
                        v-model="form.document_type"
                        required
                        autocomplete="username"
                        :disabled="true"
                    />

                    <InputError class="mt-2" :message="form.errors.document_type"/>
                </div>
            </div>
            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>
