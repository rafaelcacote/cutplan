<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Box, Shield, Truck, Users } from 'lucide-vue-next';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <!-- <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup> -->

    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarGroupLabel>Cadastros gerais</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/clientes')" tooltip="Clientes">
                    <Link href="/clientes">
                        <Users class="mr-2 h-4 w-4" />
                        <span>Clientes</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/equipes')" tooltip="Equipes">
                    <Link href="/equipes">
                        <Users class="mr-2 h-4 w-4" />
                        <span>Equipes</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/fornecedores')" tooltip="Fornecedores">
                    <Link href="/fornecedores">
                        <Truck class="mr-2 h-4 w-4" />
                        <span>Fornecedores</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/materiais')" tooltip="Materiais">
                    <Link href="/materiais">
                        <Box class="mr-2 h-4 w-4" />
                        <span>Materiais</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>

    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarGroupLabel>Gerenciamento de usuários</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/usuarios')" tooltip="Usuários">
                    <Link href="/usuarios">
                        <Users class="mr-2 h-4 w-4" />
                        <span>Usuários</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            <SidebarMenuItem>
                <SidebarMenuButton
                    as-child
                    :is-active="page.url.startsWith('/perfis') || page.url.startsWith('/permissoes')"
                    tooltip="Perfis & Permissões"
                >
                    <Link href="/roles">
                        <Shield class="mr-2 h-4 w-4" />
                        <span>Perfis & Permissões</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
