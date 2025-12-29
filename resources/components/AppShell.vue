<script setup lang="ts">
import {SidebarProvider} from "../js/components/ui/sidebar";
</script>

<template>
    <SidebarProvider>
        <slot />
    </SidebarProvider>
</template>