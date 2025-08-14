<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { AlertCircle, ArrowLeft, CheckCircle2, Clock, Edit, Hash } from 'lucide-vue-next';
import Tag from 'primevue/tag';

interface Servico {
    id: number;
    nome: string;
    descricao?: string;
    categoria?: string;
}

interface ServicoItem {
    id: number;
    descricao_item: string;
    ordem?: number;
    opcional: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    servicoItem: ServicoItem;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Itens de Serviço',
        href: '/servico-itens',
    },
    {
        title: 'Visualizar Item',
        href: `/servico-itens/${props.servicoItem.id}`,
    },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Visualizar Item de Serviço" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Item de Serviço</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Visualize os detalhes do item</p>
                </div>
                <div class="flex gap-3">
                    <Button
                        variant="outline"
                        as-child
                        class="rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                    >
                        <Link :href="route('servico-itens.index')">
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Voltar
                        </Link>
                    </Button>
                    <Button
                        as-child
                        class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                    >
                        <Link :href="route('servico-itens.edit', servicoItem.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Informações Principais -->
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader class="pb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-2xl text-[#1E293B] dark:text-white">{{ servicoItem.descricao_item }}</CardTitle>
                            <!-- Nome do serviço removido completamente -->
                        </div>
                        <Tag
                            :value="servicoItem.opcional ? 'Opcional' : 'Obrigatório'"
                            :severity="servicoItem.opcional ? 'info' : 'success'"
                            class="px-3 py-1 text-sm"
                        >
                            <template #icon>
                                <component :is="servicoItem.opcional ? AlertCircle : CheckCircle2" class="mr-1 h-4 w-4" />
                            </template>
                        </Tag>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Ordem -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                                <Hash class="h-4 w-4" />
                                Ordem de Execução
                            </div>
                            <div class="text-2xl font-bold text-[#1E293B] dark:text-white">
                                {{ servicoItem.ordem || 'Não definida' }}
                            </div>
                        </div>

                        <!-- Tipo -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                                <component :is="servicoItem.opcional ? AlertCircle : CheckCircle2" class="h-4 w-4" />
                                Tipo do Item
                            </div>
                            <div class="text-2xl font-bold text-[#1E293B] dark:text-white">
                                {{ servicoItem.opcional ? 'Opcional' : 'Obrigatório' }}
                            </div>
                        </div>

                        <!-- Data de Criação -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                                <Clock class="h-4 w-4" />
                                Criado em
                            </div>
                            <div class="text-lg font-semibold text-[#1E293B] dark:text-white">
                                {{ formatDate(servicoItem.created_at) }}
                            </div>
                        </div>

                        <!-- Última Atualização -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                                <Clock class="h-4 w-4" />
                                Atualizado em
                            </div>
                            <div class="text-lg font-semibold text-[#1E293B] dark:text-white">
                                {{ formatDate(servicoItem.updated_at) }}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Descrição Detalhada -->
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Descrição do Item</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]"> Detalhamento completo deste item do serviço </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-lg border border-[#E0E7EF] bg-[#F8FAFC] p-6 dark:border-[#475569] dark:bg-[#334155]">
                        <p class="leading-relaxed whitespace-pre-wrap text-[#1E293B] dark:text-white">{{ servicoItem.descricao_item }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Bloco de informações do serviço removido -->

            <!-- Observações do Sistema -->
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Informações do Sistema</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]"> Dados técnicos e de controle </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-1">
                            <div class="text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">ID do Item</div>
                            <div class="font-mono text-[#1E293B] dark:text-white">#{{ servicoItem.id }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">ID do Serviço</div>
                            <!-- servico_id removido -->
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
