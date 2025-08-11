<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

interface Category {
    id: number;
    nome: string;
}

interface FormaPagamento {
    id: number;
    nome: string;
}

interface Gasto {
    id: number;
    data_gasto: string;
    valor: string;
    descricao: string;
    categoria_id: number;
    forma_pagamento_id: number;
    categoria: Category;
    formaPagamento: FormaPagamento;
    created_at: string;
    updated_at: string;
}

interface Props {
    gasto: Gasto;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Gastos',
        href: '/gastos',
    },
    {
        title: `Gasto #${props.gasto.id}`,
        href: `/gastos/${props.gasto.id}`,
    },
];

const deleteGasto = () => {
    if (confirm(`Tem certeza que deseja excluir o gasto "${props.gasto.descricao}"?`)) {
        router.delete(route('gastos.destroy', props.gasto.id));
    }
};

const formatCurrency = (value: string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(parseFloat(value));
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const formatDateTime = (datetime: string) => {
    return new Date(datetime).toLocaleString('pt-BR');
};
</script>

<template>
    <Head :title="`Gasto #${gasto.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('gastos.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold tracking-tight">{{ gasto.descricao }}</h1>
                    <p class="text-muted-foreground">Detalhes do gasto</p>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child>
                        <Link :href="route('gastos.edit', gasto.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                    <Button variant="destructive" @click="deleteGasto">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Excluir
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Gasto</CardTitle>
                        <CardDescription> Dados principais do gasto </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Descrição</Label>
                            <p class="text-lg font-medium">{{ gasto.descricao }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Valor</Label>
                            <p class="text-2xl font-bold text-red-600">{{ formatCurrency(gasto.valor) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Data do Gasto</Label>
                            <p class="text-lg">{{ formatDate(gasto.data_gasto) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">ID</Label>
                            <p class="text-sm">{{ gasto.id }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Categoria e Pagamento</CardTitle>
                        <CardDescription> Informações de categoria e forma de pagamento </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Categoria</Label>
                            <p class="text-lg font-medium">{{ gasto.categoria.nome }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Forma de Pagamento</Label>
                            <p class="text-lg font-medium">{{ gasto.formaPagamento.nome }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card class="md:col-span-2">
                    <CardHeader>
                        <CardTitle>Datas do Sistema</CardTitle>
                        <CardDescription> Informações de criação e atualização </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 md:grid-cols-2">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Criado em</Label>
                            <p class="text-sm">
                                {{ formatDateTime(gasto.created_at) }}
                            </p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Atualizado em</Label>
                            <p class="text-sm">
                                {{ formatDateTime(gasto.updated_at) }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
