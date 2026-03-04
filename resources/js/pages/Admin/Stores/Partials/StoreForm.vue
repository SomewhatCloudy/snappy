<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Search, Loader2 } from 'lucide-vue-next';
import { useLocationSearch } from '@/composables/useLocationSearch';
import { useDebounceFn, onClickOutside } from '@vueuse/core';
import LocationSearchResults from '@/snappy/LocationSearchResults.vue';
import type { PostcodeData, StoreData } from '@/types';

const props = defineProps<{
    store?: StoreData;
    submitLabel: string;
}>();

const emit = defineEmits<{
    (e: 'submit', form: any): void;
}>();

const form = useForm({
    name: props.store?.name ?? '',
    postcode_id: props.store?.postcode?.id ?? (null as number | null),
    delivery_radius_km: props.store?.delivery_radius_km ?? 5,
});

const selectedPostcode = ref<PostcodeData | null>(props.store?.postcode ?? null);
const {
    searchQuery,
    locations,
    isLoading: isLoadingSearch,
    handleSearch,
} = useLocationSearch();

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
}, 300);

const selectLocation = (location: PostcodeData) => {
    selectedPostcode.value = location;
    form.postcode_id = location.id;
    searchQuery.value = location.address || location.postcode;
    isDropdownOpen.value = false;
};

watch(searchQuery, (newQuery) => {
    if (newQuery.trim().length > 2 && (!selectedPostcode.value || (newQuery !== (selectedPostcode.value.address || selectedPostcode.value.postcode)))) {
        debouncedSearch();
    } else if (newQuery.trim().length <= 2) {
        locations.value = [];
        isDropdownOpen.value = false;
    }
});

onMounted(() => {
    if (selectedPostcode.value) {
        searchQuery.value = selectedPostcode.value.address || selectedPostcode.value.postcode;
    }
});

const submit = () => {
    emit('submit', form);
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="space-y-2">
            <Label for="name">Store Name</Label>
            <Input
                id="name"
                v-model="form.name"
                placeholder="Snappy Snaps London"
                :class="{ 'border-destructive': form.errors.name }"
                required
            />
            <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
        </div>

        <div class="space-y-2">
            <Label for="postcode">Location / Postcode</Label>
            <div class="relative" ref="dropdownRef">
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        id="postcode"
                        v-model="searchQuery"
                        placeholder="Search for a location..."
                        class="pl-10"
                        :class="{ 'border-destructive': form.errors.postcode_id }"
                        autocomplete="off"
                    />
                    <div v-if="isLoadingSearch" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <Loader2 class="h-4 w-4 animate-spin text-muted-foreground" />
                    </div>
                </div>
                <LocationSearchResults
                    v-if="isDropdownOpen && locations.length > 0"
                    :locations="locations"
                    @select="selectLocation"
                />
            </div>
            <p v-if="selectedPostcode" class="text-xs text-muted-foreground">
                Selected: {{ selectedPostcode.address }} ({{ selectedPostcode.postcode }})
            </p>
            <p v-if="form.errors.postcode_id" class="text-sm text-destructive">{{ form.errors.postcode_id }}</p>
        </div>

        <div class="space-y-2">
            <Label for="radius">Delivery Radius (km)</Label>
            <Input
                id="radius"
                type="number"
                step="0.1"
                v-model="form.delivery_radius_km"
                :class="{ 'border-destructive': form.errors.delivery_radius_km }"
            />
            <p v-if="form.errors.delivery_radius_km" class="text-sm text-destructive">{{ form.errors.delivery_radius_km }}</p>
        </div>

        <div class="flex items-center gap-4">
            <Button type="submit" :disabled="form.processing">
                {{ submitLabel }}
            </Button>
            <Button variant="outline" type="button" @click="$window.history.back()">
                Cancel
            </Button>
        </div>
    </form>
</template>
