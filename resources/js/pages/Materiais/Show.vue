<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

interface TipoMaterial {
    id: number;
    nome: string;
}

interface Unidade {
    id: number;
    nome: string;
    codigo: string;
}

interface Fornecedor {
    id: number;
    nome: string;
}

interface Material {
    id: number;
    sku: string;
    nome: string;
    tipo_id: number;
    unidade_id: number;
    fornecedor_padrao_id?: number;
    preco_custo?: string;
    estoque_minimo?: string;
    controla_estoque: boolean;
    ativo: boolean;
    tipo?: TipoMaterial;
    unidade?: Unidade;
    fornecedorPadrao?: Fornecedor;
    created_at: string;
    updated_at: string;
}

interface Props {
    material: Material;
}


const props = defineProps<Props>();
const { material } = props;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Materiais',
        href: '/materiais',
    },
    {
        title: `Material #${material.id}`,
        href: `/materiais/${material.id}`,
    },
];

const deleteMaterial = () => {
    if (confirm(`Tem certeza que deseja excluir o material "${material.nome}"?`)) {
        router.delete(route('materiais.destroy', { materiai: material.id }));
    }
};

const formatCurrency = (value?: string) => {
    if (!value) return '-';
    return Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

const formatDateTime = (datetime: string) => {
    return new Date(datetime).toLocaleString('pt-BR');
};

const formatBoolean = (val: boolean) => (val ? 'Sim' : 'Não');
</script>

<template>
    <Head :title="`Material #${material.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300 rounded-xl">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('materiais.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">{{ material.nome }}</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Detalhes do material</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                        <Link :href="route('materiais.edit', { materiai: material.id })">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                    <Button variant="destructive" @click="deleteMaterial" class="px-6 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Excluir
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Básicas</CardTitle>
                        <CardDescription>Dados principais do material</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">SKU</Label>
                            <p class="text-lg font-mono">{{ material.sku }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Nome</Label>
                            <p class="text-lg font-medium">{{ material.nome }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Tipo</Label>
                            <p class="text-lg">{{ material.tipo?.nome || '-' }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Unidade</Label>
                            <p class="text-lg">{{ material.unidade?.nome || '-' }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Fornecedor Padrão</Label>
                            <p class="text-lg">{{ material.fornecedorPadrao?.nome || '-' }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Informações Comerciais</CardTitle>
                        <CardDescription>Dados de preço e estoque</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Preço de Custo</Label>
                            <p class="text-lg font-semibold">{{ formatCurrency(material.preco_custo) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Estoque Mínimo</Label>
                            <p class="text-lg">{{ material.estoque_minimo || '-' }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Controla Estoque</Label>
                            <p class="text-lg" :class="material.controla_estoque ? 'text-green-600' : 'text-amber-600'">
                                {{ formatBoolean(material.controla_estoque) }}
                            </p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                            <p class="text-lg" :class="material.ativo ? 'text-green-600' : 'text-red-500'">
                                {{ formatBoolean(material.ativo) }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Informações do Sistema</CardTitle>
                    <CardDescription>Dados de controle do sistema</CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4 md:grid-cols-2">
                    <div>
                        <Label class="text-sm font-medium text-muted-foreground">Criado em</Label>
                        <p class="text-lg">{{ formatDateTime(material.created_at) }}</p>
                    </div>
                    <div>
                        <Label class="text-sm font-medium text-muted-foreground">Última atualização</Label>
                        <p class="text-lg">{{ formatDateTime(material.updated_at) }}</p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
