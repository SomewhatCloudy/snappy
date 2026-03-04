<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, destroy, edit } from '@/routes/admin/stores';

defineProps({
    stores: Array,
});

const form = useForm({});

const deleteStore = (id) => {
    if (confirm('Are you sure you want to delete this store?')) {
        form.delete(destroy.url(id));
    }
};
const breadcrumbs = [
    { title: 'Stores', href: '/admin/stores' },
];
</script>

<template>
    <Head title="Stores" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex justify-between mb-4">
                    <h2 class="text-2xl font-semibold">Store Management</h2>
                    <Link :href="create.url()" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Add Store
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Postcode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Radius (km)</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="store in stores" :key="store.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ store.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ store.postcode.postcode }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ store.delivery_radius_km }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="edit.url(store.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                    <button @click="deleteStore(store.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
