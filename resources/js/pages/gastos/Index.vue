<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Eye, Plus, Trash2 } from 'lucide-vue-next';

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
    gastos: {
        data: Gasto[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Gastos',
        href: '/gastos',
    },
];

const deleteGasto = (gasto: Gasto) => {
    if (confirm(`Tem certeza que deseja excluir o gasto "${gasto.descricao}"?`)) {
        router.delete(`/gastos/${gasto.id}`);
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
</script>

<template>
    <Head title="Gastos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Gastos</h1>
                    <p class="text-muted-foreground">Gerencie seus gastos</p>
                </div>
                <Button as-child>
                    <Link :href="route('gastos.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Gasto
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Lista de Gastos</CardTitle>
                    <CardDescription> Total de {{ gastos.total }} gastos encontrados </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="gastos.data.length === 0" class="py-8 text-center">
                        <p class="text-muted-foreground">Nenhum gasto encontrado.</p>
                        <Button as-child class="mt-4">
                            <Link :href="route('gastos.create')">
                                <Plus class="mr-2 h-4 w-4" />
                                Criar primeiro gasto
                            </Link>
                        </Button>
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="gasto in gastos.data"
                            :key="gasto.id"
                            class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-muted/50"
                        >
                            <div class="flex-1">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-medium">{{ gasto.descricao }}</h3>
                                        <p class="text-sm text-muted-foreground">
                                            {{ formatDate(gasto.data_gasto) }} • {{ gasto.categoria.nome }} • {{ gasto.formaPagamento.nome }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold">{{ formatCurrency(gasto.valor) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="route('gastos.show', gasto.id)">
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="route('gastos.edit', gasto.id)">
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" @click="deleteGasto(gasto)" class="text-destructive hover:text-destructive">
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="gastos.last_page > 1" class="mt-6 flex items-center justify-center gap-2">
                            <Link
                                v-for="link in gastos.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                :class="[
                                    'rounded-md px-3 py-2 text-sm transition-colors',
                                    link.active ? 'bg-primary text-primary-foreground' : 'hover:bg-muted',
                                    !link.url ? 'pointer-events-none opacity-50' : '',
                                ]"
                            >
                                {{ link.label.replace('&laquo;', '«').replace('&raquo;', '»') }}
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
