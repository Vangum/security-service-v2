<script setup lang="ts">
import AppShell from "@/components/AppShell.vue";
import AppSidebar from "@/components/AppSidebar.vue";
import AppContent from "@/components/AppContent.vue";
import AppSidebarHeader from "@/components/AppSidebarHeader.vue";
import {BreadcrumbItemType} from "@/types";

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar" class="h-screen">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden overflow-y-auto flex flex-col h-full">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" class="flex-shrink-0" />
            <div class="flex-1 overflow-y-auto">
                <slot />
            </div>
        </AppContent>
    </AppShell>
</template>