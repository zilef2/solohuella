<script setup lang="ts">
import NavFooter from '@/Components/NavFooter.vue';
import NavMain from '@/Components/NavMain.vue';
import NavUser from '@/Components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/Components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Procesos Rama Judicial',
        href: '/rama',
        icon: LayoutGrid,
    },
];

const footerSuperNavItems: NavItem[] = [
    {
        title: 'Roles & Permissions',
        href: '/permission',
        icon: Folder,
    },
    {
        title: 'Parametros',
        href: '/Parametros',
        icon: BookOpen,
    },
];
const footerNavItems: NavItem[] = [
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerSuperNavItems" />
        </SidebarFooter>
        <SidebarFooter>
            <NavFooter :items="footerNavItems" /><NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
