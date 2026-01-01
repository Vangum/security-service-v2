<script setup lang="ts">
import {SidebarTrigger} from "@/components/ui/sidebar";
import {BreadcrumbItemType} from "@/types";
import Breadcrumbs from "@/components/Breadcrumbs.vue";
import {router, usePage} from "@inertiajs/vue3";
import {Button} from "@/components/ui/button";
import {LogOut} from 'lucide-vue-next';
import {logout} from "@/routes";

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <header class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="ml-auto flex items-center gap-4">
            {{ user.name }}
            <Button @click.prevent="router.post(logout().url)" variant="outline" size="icon-sm">
                <LogOut />
            </Button>
        </div>
    </header>
</template>