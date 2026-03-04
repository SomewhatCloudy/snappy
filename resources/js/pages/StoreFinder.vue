<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import StoreCard from '@/snappy/StoreCard.vue';
import LocationSearch from '@/snappy/LocationSearch.vue';
import { MapPin, Loader2 } from 'lucide-vue-next';
import { useNearbyStores } from '@/composables/useNearbyStores';
import AppPageLayout from "@/layouts/AppPageLayout.vue";
import type { Coords } from '@/types';

// Find stores nearby
const {
    stores,
    isLoading: isLoadingNearby,
    error: nearbyError,
    fetchNearbyStores,
} = useNearbyStores();

const currentUserCoords = ref<Coords | null>(null);

const handleLocationSelected = (coords: Coords) => {
    currentUserCoords.value = coords;
    fetchNearbyStores(coords);
};

const isLoading = computed(() => isLoadingNearby.value);
const error = computed(() => nearbyError.value);

</script>

<template>
    <AppPageLayout>
        <Head title="Store Finder" />

        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 container mx-auto">
            <LocationSearch @location-selected="handleLocationSelected" />

            <div v-if="error" class="rounded-lg bg-destructive/10 p-4 text-sm text-destructive">
                {{ error }}
            </div>

            <div v-if="stores.length > 0" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <StoreCard
                    v-for="store in stores"
                    :key="store.id"
                    :store="store"
                    :user-coords="currentUserCoords || undefined"
                />
            </div>

            <div v-else-if="!isLoading" class="flex flex-1 items-center justify-center rounded-lg border border-dashed p-8 text-center">
                <div class="flex flex-col items-center gap-2">
                    <MapPin class="h-8 w-8 text-muted-foreground" />
                    <p class="text-lg font-medium">No stores found</p>
                    <p class="text-sm text-muted-foreground">Try searching for a different location or use your current position.</p>
                </div>
            </div>

            <div v-else class="flex flex-1 items-center justify-center">
                <Loader2 class="h-8 w-8 animate-spin text-muted-foreground" />
            </div>
        </div>
    </AppPageLayout>
</template>
