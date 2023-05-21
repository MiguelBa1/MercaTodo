<template>
  <Link :href="href" class="bg-white overflow-hidden shadow rounded-lg">
    <div class="p-6">
      <div class="flex items-center mb-3">
          <slot />
        <h5 class="text-xl font-semibold pl-2">{{ title }}</h5>
      </div>
      <p class="text-gray-600">{{ subTitle }}</p>
    </div>
  </Link>
</template>
<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    title: {
        type: String,
        required: true
    },
    subTitle: {
        type: String,
        required: true
    },
    href: {
        type: String,
        required: true
    }
})
</script>
