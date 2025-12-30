<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head} from '@inertiajs/vue3';
import {visitorsCreate} from "@/routes";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Добавить посетителя',
        href: visitorsCreate().url,
    },
];

</script>

<template>
    <Head title="Добавить посетителя" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1>Добавить посетителя</h1>
        </div>
    </AppLayout>
</template>
