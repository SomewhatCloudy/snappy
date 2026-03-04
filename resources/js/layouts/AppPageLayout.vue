<script setup lang="ts">
import { index } from '@/routes/admin/stores';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <div class="flex flex-col min-h-[90vh] container mx-auto px-4 py-8">
        <div class="grow">
            <slot />
        </div>
        <div class="text-center">
            <Link :href="index.url()" class="inline-block bg-primary text-white px-4 py-2 mb-5 rounded-md">Admin Area</Link>
            <p class="font-mono text-sm">Authentication: <span class="text-gray-600">admin@example.org / password</span></p>
        </div>
    </div>
</template>
