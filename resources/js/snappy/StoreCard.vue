<script setup lang="ts">
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import type {Coords, StoreData} from '@/types';
import { useDistance } from '@/composables/useDistance';

const props = defineProps<{
    store: StoreData;
    userCoords?: Coords;
}>();

const { calculateDistance } = useDistance();

const canDeliver = computed(() => {
    if (!props.userCoords || !props.store.delivery_radius_km || !props.store.postcode) {
        return null;
    }

    const distance = distanceInKm.value;
    if (distance === null) return null;

    return distance <= props.store.delivery_radius_km;
});

const distanceInKm = computed(() => {
    if (props.store.distance_km !== null && props.store.distance_km !== undefined) {
        return props.store.distance_km;
    }

    if (!props.userCoords || !props.store.postcode) {
        return null;
    }

    const distance = calculateDistance(props.userCoords, props.store.postcode);
    return isNaN(distance) ? null : distance;
});
</script>

<template>
    <Card>
        <CardHeader>
            <div class="flex items-start justify-between">
                <div>
                    <CardTitle>{{ store.name }}</CardTitle>
                    <CardDescription>{{ store.postcode.address }}</CardDescription>
                    <p v-if="distanceInKm !== null" class="mt-1 text-sm font-medium text-primary">
                        {{ distanceInKm.toFixed(2) }} km away
                    </p>
                </div>
                <div v-if="canDeliver !== null">
                    <Badge v-if="canDeliver" variant="default" class="bg-green-500 hover:bg-green-600">
                        Can Deliver
                    </Badge>
                    <Badge v-else variant="destructive">
                        Too Far
                    </Badge>
                </div>
            </div>
        </CardHeader>
        <CardContent>
            <p v-if="store.delivery_radius_km" class="text-sm text-muted-foreground">
                Delivery Radius: {{ store.delivery_radius_km }}km
            </p>
        </CardContent>
    </Card>
</template>
