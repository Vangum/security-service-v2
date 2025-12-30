<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head} from '@inertiajs/vue3';
import {visitorsIndex} from "@/routes";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Список посетителей',
        href: visitorsIndex().url,
    },
];

</script>

<template>
    <Head title="Список посетителей" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1>Список посетителей</h1>
        </div>
    </AppLayout>
</template>
