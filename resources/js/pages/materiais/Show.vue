<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2, Package } from 'lucide-vue-next';

interface TipoMaterial {
    id: number;
    nome: string;
}

interface Unidade {
    id: number;
    codigo: string;
    nome: string;
}

interface Fornecedor {
    id: number;
    nome: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Material {
    id: number;
    sku: string;
    nome: string;
    tipo_id: number;
    unidade_id: number;
    fornecedor_padrao_id?: number;
    preco_custo?: number;
    estoque_minimo?: number;
    controla_estoque: boolean;
    ativo: boolean;
    tipo: TipoMaterial;
    unidade: Unidade;
    fornecedor_padrao?: Fornecedor;
    user?: User;
    created_at: string;
    updated_at: string;
}

interface Props {
    material: Material;
}

const props = defineProps<Props>();

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
        title: `Material #${props.material.id}`,
        href: `/materiais/${props.material.id}`,
    },
];

const deleteMaterial = () => {
    if (confirm(`Tem certeza que deseja excluir o material "${props.material.nome}"?`)) {
        router.delete(route('materiais.destroy', props.material.id));
    }
};

const formatDateTime = (datetime: string) => {
    return new Date(datetime).toLocaleString('pt-BR');
};

const formatCurrency = (value?: number) => {
    if (!value) return '-';
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
};

const formatNumber = (value?: number) => {
    if (!value) return '-';
    return new Intl.NumberFormat('pt-BR').format(value);
};
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
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA] flex items-center gap-2">
                            <Package class="h-8 w-8" />
                            {{ material.nome }}
                        </h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">SKU: {{ material.sku }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                        <Link :href="route('materiais.edit', material.id)">
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
                            <p class="text-lg font-mono font-medium">{{ material.sku }}</p>
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
                            <p class="text-lg">{{ material.unidade?.codigo }} - {{ material.unidade?.nome }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Fornecedor Padrão</Label>
                            <p class="text-lg">{{ material.fornecedor_padrao?.nome || 'Não definido' }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Informações Financeiras e Estoque</CardTitle>
                        <CardDescription>Dados de custos e controle</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Preço de Custo</Label>
                            <p class="text-lg font-mono">{{ formatCurrency(material.preco_custo) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Estoque Mínimo</Label>
                            <p class="text-lg font-mono">{{ formatNumber(material.estoque_minimo) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Controla Estoque</Label>
                            <span 
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                :class="material.controla_estoque 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'"
                            >
                                {{ material.controla_estoque ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                            <br>
                            <span 
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                :class="material.ativo 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'"
                            >
                                {{ material.ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Informações do Sistema</CardTitle>
                    <CardDescription>Dados de controle do sistema</CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4 md:grid-cols-3">
                    <div>
                        <Label class="text-sm font-medium text-muted-foreground">Criado em</Label>
                        <p class="text-lg">{{ formatDateTime(material.created_at) }}</p>
                    </div>
                    <div>
                        <Label class="text-sm font-medium text-muted-foreground">Última atualização</Label>
                        <p class="text-lg">{{ formatDateTime(material.updated_at) }}</p>
                    </div>
                    <div v-if="material.user">
                        <Label class="text-sm font-medium text-muted-foreground">Cadastrado por</Label>
                        <p class="text-lg">{{ material.user.name }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Card de informações adicionais -->
            <Card v-if="material.controla_estoque">
                <CardHeader>
                    <CardTitle>Informações de Estoque</CardTitle>
                    <CardDescription>Detalhes sobre o controle de estoque</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Controle de Estoque Ativo</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-400">
                            Este material tem controle de estoque ativado. O sistema gerenciará automaticamente 
                            as quantidades disponíveis baseadas nas movimentações de entrada e saída.
                        </p>
                        <p v-if="material.estoque_minimo" class="text-sm text-blue-700 dark:text-blue-400 mt-2">
                            <strong>Estoque Mínimo:</strong> {{ formatNumber(material.estoque_minimo) }} {{ material.unidade?.codigo }}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
