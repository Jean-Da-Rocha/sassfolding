<script setup lang="ts">
const props = defineProps<{
    status: number;
}>();

const title = computed(() => ({
    403: 'Forbidden',
    404: 'Not found',
    503: 'Service unavailable',
})[props.status] ?? 'Internal server error');

const description = computed(() => ({
    403: 'Sorry, you cannot access this page.',
    404: 'The page your are looking for does not exist.',
    503: 'Sorry, we are doing some maintenance. Please check back soon.',
})[props.status] ?? 'Sorry, something went wrong.');

function redirectBackToPreviousUrl() {
    window.history.back();
}
</script>

<template>
    <div class="flex h-screen items-center justify-center bg-white">
        <div class="text-center">
            <p class="text-4xl font-bold text-blue-400">
                {{ props.status }} | {{ title }}
            </p>
            <div class="mt-6">
                <p class="text-gray-500">
                    {{ description }}
                </p>
            </div>
            <div class="mt-6">
                <Button @click="redirectBackToPreviousUrl" label="Go back" severity="info" />
            </div>
        </div>
    </div>
</template>
