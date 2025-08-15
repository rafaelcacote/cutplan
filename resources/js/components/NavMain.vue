<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Box, Briefcase, Layers, Settings, Shield, Truck, Users } from 'lucide-vue-next';
import 'primeicons/primeicons.css';
import { ref } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const cadastrosOpen = ref(false);
const configOpen = ref(false);

import { watchEffect } from 'vue';

// Rotas dos submenus
const cadastrosRoutes = ['/clientes', '/contatos', '/fornecedores', '/materiais'];
const configRoutes = ['/servicos', '/servico-itens', '/equipes'];

// Abrir automaticamente se a rota atual for de algum subitem
watchEffect(() => {
    cadastrosOpen.value = cadastrosRoutes.some(route => page.url.startsWith(route));
    configOpen.value = configRoutes.some(route => page.url.startsWith(route));
});
</script>

<template>
    <!-- Dashboard -->
    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/dashboard')" tooltip="Dashboard">
                    <Link href="/dashboard">
                        <i class="pi pi-th-large mr-2" style="font-size: 1.1rem" />
                        <span>Dashboard</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>

    <!-- Cadastros -->
    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarMenuSubButton as="button" class="w-full justify-between" @click="cadastrosOpen = !cadastrosOpen">
            <span class="flex items-center">
                <i class="pi pi-plus mr-2" style="font-size: 1.1rem" />
                Cadastros
            </span>
            <svg
                :class="['transition-transform', cadastrosOpen ? 'rotate-90' : '']"
                width="16"
                height="16"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
            >
                <path d="M9 5l7 7-7 7" />
            </svg>
        </SidebarMenuSubButton>
        <SidebarMenuSub v-if="cadastrosOpen">
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/clientes')" tooltip="Clientes">
                    <Link href="/clientes">
                        <Users class="mr-2 h-4 w-4" />
                        <span>Clientes</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/contatos')" tooltip="Contatos">
                    <Link href="/contatos">
                        <Users class="mr-2 h-4 w-4" />
                        <span>Contatos</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/fornecedores')" tooltip="Fornecedores">
                    <Link href="/fornecedores">
                        <Truck class="mr-2 h-4 w-4" />
                        <span>Fornecedores</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/materiais')" tooltip="Materiais">
                    <Link href="/materiais">
                        <Box class="mr-2 h-4 w-4" />
                        <span>Materiais</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
        </SidebarMenuSub>
    </SidebarGroup>

    <!-- Orçamentos -->
    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton as-child :is-active="page.url.startsWith('/orcamentos')" tooltip="Orçamentos">
                    <Link href="/orcamentos">
                        <i class="pi pi-file-edit mr-2" style="font-size: 1.1rem" />
                        <span>Orçamentos</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>

    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarMenuSubButton as="button" class="w-full justify-between" @click="configOpen = !configOpen">
            <span class="flex items-center">
                <Settings class="mr-2 h-4 w-4" />
                Configurações
            </span>
            <svg
                :class="['transition-transform', configOpen ? 'rotate-90' : '']"
                width="16"
                height="16"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
            >
                <path d="M9 5l7 7-7 7" />
            </svg>
        </SidebarMenuSubButton>
        <SidebarMenuSub v-if="configOpen">
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/servicos')" tooltip="Serviços">
                    <Link href="/servicos">
                        <Briefcase class="mr-2 h-4 w-4" />
                        <span>Serviços</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/servico-itens')" tooltip="Itens de Serviço">
                    <Link href="/servico-itens">
                        <Layers class="mr-2 h-4 w-4" />
                        <span>Itens de Serviço</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
            <SidebarMenuSubItem>
                <SidebarMenuSubButton as-child :is-active="page.url.startsWith('/equipes')" tooltip="Equipes">
                    <Link href="/equipes">
                        <Users class="mr-2 h-4 w-4" />
                        <span>Equipes</span>
                    </Link>
                </SidebarMenuSubButton>
            </SidebarMenuSubItem>
        </SidebarMenuSub>
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
