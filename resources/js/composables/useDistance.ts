import { type Coords } from '@/types';

export function useDistance() {
    const calculateDistance = (from: Coords, to: { lat: number, lon: number } | { latitude: number, longitude: number }): number => {
        const R = 6371; // Earth radius in km

        const toLat = 'lat' in to ? to.lat : to.latitude;
        const toLon = 'lon' in to ? to.lon : to.longitude;

        const dLat = (from.lat - toLat) * Math.PI / 180;
        const dLon = (from.lon - toLon) * Math.PI / 180;

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toLat * Math.PI / 180) * Math.cos(from.lat * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    };

    return {
        calculateDistance,
    };
}
