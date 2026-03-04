import { ref } from 'vue';
import type { PostcodeData } from '@/types';
import { searchLocations } from '@/actions/App/Http/Controllers/Api/StoreController';

export function useLocationSearch() {
    const searchQuery = ref('');
    const locations = ref<PostcodeData[]>([]);
    const isLoading = ref(false);
    const error = ref<string | null>(null);

    const handleSearch = async () => {
        if (!searchQuery.value.trim()) {
            locations.value = [];
            return;
        }

        isLoading.value = true;
        error.value = null;
        try {
            // First search for locations
            const locationResponse = await fetch(searchLocations.url({
                query: { query: searchQuery.value }
            }));
            if (!locationResponse.ok) {
                throw new Error('Location search failed');
            }
            const data = await locationResponse.json();
            locations.value = data;

            if (data.length === 0) {
                error.value = 'Location not found';
            }
        } catch (e: any) {
            error.value = e.message;
            locations.value = [];
        } finally {
            isLoading.value = false;
        }
    };

    return {
        searchQuery,
        locations,
        isLoading,
        error,
        handleSearch,
    };
}
