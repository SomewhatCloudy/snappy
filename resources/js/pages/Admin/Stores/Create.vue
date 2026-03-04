<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import StoreForm from './Partials/StoreForm.vue';
import { store } from '@/routes/admin/stores';

const breadcrumbs = [
    { title: 'Stores', href: '/admin/stores' },
    { title: 'Create Store', href: '/admin/stores/create' },
];

const handleSubmit = (form: any) => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Store" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-6">Create New Store</h2>
                    <StoreForm submit-label="Create Store" @submit="handleSubmit" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
