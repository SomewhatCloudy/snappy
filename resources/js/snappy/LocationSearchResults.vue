<script setup lang="ts">
import type { PostcodeData } from '@/types';

defineProps<{
    locations: PostcodeData[];
}>();

const emit = defineEmits<{
    (e: 'select', location: PostcodeData): void;
}>();
</script>

<template>
    <div class="absolute z-50 mt-1 w-full overflow-y-auto rounded-md border bg-popover p-1 shadow-md max-h-[300px] bg-white">
        <ul class="py-1">
            <li
                v-for="location in locations"
                :key="location.id"
                @click="emit('select', location)"
                class="relative flex cursor-pointer select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
            >
                <div class="flex flex-col">
                    <span class="font-medium">{{ location.address || location.postcode }}</span>
                    <span v-if="location.address" class="text-xs text-muted-foreground">{{ location.postcode }}</span>
                </div>
            </li>
        </ul>
    </div>
</template>
