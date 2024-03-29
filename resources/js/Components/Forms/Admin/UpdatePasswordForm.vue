<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useToast } from "vue-toast-notification";
import { ref } from 'vue';
import axios from "axios";

const $toast = useToast();

const passwordInput = ref(null);

const form = useForm({
    password: '',
    password_confirmation: '',
});

const user = usePage().props.user;

const updatePassword = () => {
    if (form.data().password !== form.data().password_confirmation) {
        $toast.error('Passwords do not match.');
        return;
    }
    axios.patch(route('api.admin.users.password.update', user.id), form.data())
        .then(response => {
            $toast.success('Password updated successfully.');
            form.reset();
        })
        .catch(error => {
            if (error.response.data.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
                $toast.error(error.response.data.errors.password[0]);
            }
        });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
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
