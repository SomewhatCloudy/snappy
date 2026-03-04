import { ref } from 'vue';
import type {Coords, StoreData} from '@/types';
import { nearbyCoords } from '@/actions/App/Http/Controllers/Api/StoreController';

export function useNearbyStores() {
    const stores = ref<StoreData[]>([]);
    const isLoading = ref(false);
    const error = ref<string | null>(null);

    const fetchNearbyStores = async (coords: Coords) => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await fetch(nearbyCoords.url({
                query: {
                    lat: coords.lat,
                    lon: coords.lon,
                }
            }));
            if (!response.ok) {
                throw new Error('Failed to fetch stores');
            }
            stores.value = await response.json();
        } catch (e: any) {
            error.value = e.message;
            stores.value = [];
            throw e;
        } finally {
            isLoading.value = false;
        }
    };

    return {
        stores,
        isLoading,
        error,
        fetchNearbyStores,
    };
}
