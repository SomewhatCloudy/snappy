<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Search, MapPin, Loader2 } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import LocationSearchResults from '@/snappy/LocationSearchResults.vue';
import { useLocationSearch } from '@/composables/useLocationSearch';
import { useGeolocation } from '@/composables/useGeolocation';
import { useDebounceFn, onClickOutside } from '@vueuse/core';
import type { PostcodeData, Coords } from '@/types';

const emit = defineEmits<{
    (e: 'location-selected', coords: Coords): void;
}>();

// Location search
const {
    searchQuery,
    locations,
    isLoading: isLoadingSearch,
    handleSearch,
} = useLocationSearch();

// Geolocation
const {
    isLoading: isLoadingGeo,
    handleUseLocation: originalHandleUseLocation,
} = useGeolocation();

const isDropdownOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

onClickOutside(dropdownRef, () => {
    isDropdownOpen.value = false;
});

const debouncedSearch = useDebounceFn(() => {
    handleSearch().then(() => {
        if (locations.value.length > 0) {
            isDropdownOpen.value = true;
        }
    });
}, 200);

const selectLocationCallback = (location: PostcodeData) => {
    isDropdownOpen.value = false;
    emit('location-selected', { lat: location.latitude, lon: location.longitude });
};

const handleUseLocation = () => {
    originalHandleUseLocation((coords: Coords) => {
        emit('location-selected', coords);
    });
};

watch(searchQuery, (newQuery) => {
    if (newQuery.trim().length > 2) {
        debouncedSearch();
    } else {
        locations.value = [];
        isDropdownOpen.value = false;
    }
});

const isLoading = computed(() => isLoadingSearch.value || isLoadingGeo.value);

</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-4 md:flex-row md:items-center">
            <div class="relative flex-1" ref="dropdownRef">
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Enter location or postcode..."
                        class="pl-10"
                        @keyup.enter="handleSearch"
                        @focus="locations.length > 0 && (isDropdownOpen = true)"
                    />
                </div>

                <LocationSearchResults
                    v-if="isDropdownOpen && locations.length > 0"
                    :locations="locations"
                    @select="selectLocationCallback"
                />
            </div>
            <div class="flex gap-2">
                <Button :disabled="isLoading" @click="handleSearch">
                    <Search v-if="!isLoading" class="mr-2 h-4 w-4" />
                    <Loader2 v-else class="mr-2 h-4 w-4 animate-spin" />
                    Search
                </Button>
                <Button variant="outline" :disabled="isLoading" @click="handleUseLocation">
                    <MapPin class="mr-2 h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>
