import { ref } from 'vue';
import {Coords} from "@/types";

export function useGeolocation() {
    const isLoading = ref(false);
    const error = ref<string | null>(null);
    let locationChangeCallback: (coords: Coords) => void = (coords: Coords) => {}

    const handleUseLocation = (onSuccess?: (coords: Coords) => void) => {
        if (!navigator.geolocation) {
            error.value = 'Geolocation is not supported by your browser';
            return;
        }

        isLoading.value = true;
        error.value = null;

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                const coords: Coords = { lat, lon };
                if (onSuccess) onSuccess(coords);
                locationChangeCallback(coords);
                isLoading.value = false;
            },
            (err) => {
                error.value = err.message;
                isLoading.value = false;
            }
        );
    };

    return {
        isLoading,
        error,
        handleUseLocation,
    };
}
