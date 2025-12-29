<script setup lang="ts">
import {Sidebar, SidebarContent} from "@/components/ui/sidebar";
import NavMain from "@/components/NavMain.vue";
import { File, Users, ChartSpline } from 'lucide-vue-next';
import {NavItem} from "@/types";

const mainNavItems: NavItem[] = [
    {
        title: 'Посетители',
        href: '/',
        icon: Users,
    },
    {
        title: 'Справочники',
        href: '/departments',
        icon: File,
    },
    {
        title: 'Дашборды',
        href: '/dashboard',
        icon: ChartSpline,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>
    </Sidebar>
</template>