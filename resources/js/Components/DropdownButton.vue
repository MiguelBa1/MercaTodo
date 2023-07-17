<script setup>
const props = defineProps({
    action: Function,
});

const handleAction = () => {
    props.action();
};
</script>

<template>
    <button
        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
        @click="handleAction"
    >
        <slot />
    </button>
</template>
