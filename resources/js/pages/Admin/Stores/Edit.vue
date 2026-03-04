<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StoreForm from './Partials/StoreForm.vue';
import { update } from '@/routes/admin/stores';
import type { StoreData } from '@/types';

const props = defineProps<{
    store: StoreData;
}>();

const breadcrumbs = [
    { title: 'Stores', href: '/admin/stores' },
    { title: 'Edit Store', href: `/admin/stores/${props.store.id}/edit` },
];

const handleSubmit = (form: any) => {
    form.put(update.url(props.store.id));
};
</script>

<template>
    <Head title="Edit Store" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-6">Edit Store: {{ store.name }}</h2>
                    <StoreForm :store="store" submit-label="Update Store" @submit="handleSubmit" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
