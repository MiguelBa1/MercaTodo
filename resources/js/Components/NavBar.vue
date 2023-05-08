<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {Link} from "@inertiajs/vue3";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import {ref} from "vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link :href="route('home')">
                        <ApplicationLogo/>
                    </Link>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Settings Dropdown -->
                    <div v-if="$page.props.auth.user" class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg class="h-6 w-6 text-black" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round">  <path
                                                    d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>  <circle
                                                    cx="12" cy="7" r="4"/></svg>
                                                <svg
                                                    class=" -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <div v-if="$page.props.auth.isAdmin">
                                    <DropdownLink v-if="$page.props.auth.isAdmin" :href="route('admin.dashboard')">
                                        Administrator
                                    </DropdownLink>
                                    <DropdownLink :href="route('admin.view.users')" class="pl-8 flex">
                                        <div class="border border-indigo-100 mr-3"></div>
                                        Users
                                    </DropdownLink>
                                    <DropdownLink :href="route('admin.view.products')" class="pl-8 flex">
                                        <div class="border border-indigo-100 mr-3"></div>
                                        Products
                                    </DropdownLink>
                                    <DropdownLink :href="route('admin.auxiliary.tables.index')" class="pl-8 flex">
                                        <div class="border border-indigo-100 mr-3"></div>
                                        Auxiliary Tables
                                    </DropdownLink>
                                </div>
                                <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <div v-else class="">
                        <Link
                            :href="route('login')"
                            class="font-semibold text-gray-400 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Log in
                        </Link>

                        <Link
                            :href="route('register')"
                            class="ml-4 font-semibold text-gray-400 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Register
                        </Link
                        >
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden"
        >
            <!-- Responsive Settings Options -->
            <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink v-if="$page.props.auth.isAdmin" :href="route('admin.dashboard')"> Administrator
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.isAdmin" :href="route('admin.view.users')"><span
                        class="border-l-4 pl-2">Users</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.isAdmin" :href="route('admin.view.products')"><span
                        class="border-l-4 pl-2">Products</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.isAdmin" :href="route('admin.auxiliary.tables.index')"><span
                        class="border-l-4 pl-2">Auxiliary Tables</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Log Out
                    </ResponsiveNavLink>
                </div>
            </div>
            <div v-else class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('register')"> Register</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('login')"> Log In</ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
