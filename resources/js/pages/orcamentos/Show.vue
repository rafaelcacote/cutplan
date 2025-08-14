<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Download, Edit, Send } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import { computed } from 'vue';

interface Cliente {
    id: number;
    nome: string;
    documento?: string;
    email?: string;
    telefone?: string;
    endereco?: {
        linha1: string;
        linha2?: string;
        cidade: string;
        estado: string;
        cep?: string;
        pais?: string;
    };
}

interface User {
    id: number;
    name: string;
}

interface ItemOrcamento {
    id: number;
    servico_id?: number;
    servicos_itens_id?: number;
    descricao: string;
    quantidade: string;
    unidade_id?: number;
    preco_unitario: string;
    total: string;
    eh_servico: boolean;
    servico?: {
        id: number;
        nome: string;
    };
    servico_item?: {
        id: number;
        descricao_item: string;
    };
    unidade?: {
        id: number;
        codigo: string;
        nome: string;
    };
}

interface Orcamento {
    id: number;
    cliente_id: number;
    versao: number;
    status: string;
    status_label: string;
    moeda: string;
    subtotal: string;
    desconto: string;
    impostos: string;
    total: string;
    validade?: string;
    criado_por: number;
    observacoes?: string;
    cliente: Cliente;
    criado_por_user: User;
    itens: ItemOrcamento[];
    created_at: string;
    updated_at: string;
}

interface Props {
    orcamento: Orcamento;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orçamentos', href: '/orcamentos' },
    { title: `Orçamento #${props.orcamento.id}`, href: '#' },
];

// Formatação
const formatCurrency = (value: string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(parseFloat(value));
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('pt-BR');
};

const getStatusSeverity = (status: string) => {
    switch (status) {
        case 'draft':
            return 'secondary';
        case 'sent':
            return 'info';
        case 'approved':
            return 'success';
        case 'rejected':
            return 'danger';
        case 'expired':
            return 'warning';
        default:
            return 'secondary';
    }
};

const getStatusVariant = (status: string) => {
    switch (status) {
        case 'draft':
            return 'secondary';
        case 'sent':
            return 'default';
        case 'approved':
            return 'success';
        case 'rejected':
            return 'destructive';
        case 'expired':
            return 'warning';
        default:
            return 'secondary';
    }
};

const isExpired = computed(() => {
    if (!props.orcamento.validade) return false;
    return new Date(props.orcamento.validade) < new Date();
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="`Orçamento #${orcamento.id}`" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <div class="mb-2 flex items-center gap-4">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Orçamento #{{ orcamento.id }}</h1>
                        <Tag :value="orcamento.status_label" :severity="getStatusSeverity(orcamento.status)" />
                        <Badge v-if="isExpired" variant="destructive"> Expirado </Badge>
                    </div>
                    <p class="text-gray-600">Criado em {{ formatDateTime(orcamento.created_at) }} por {{ orcamento.criado_por_user.name }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        :href="`/orcamentos`"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Voltar
                    </Link>
                    <Link
                        :href="`/orcamentos/${orcamento.id}/edit`"
                        class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                    >
                        <Edit class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Informações Principais -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Informações do Orçamento -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Informações do Orçamento</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <Badge :variant="getStatusVariant(orcamento.status)" class="mt-1">
                                        {{ orcamento.status_label }}
                                    </Badge>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Versão</p>
                                    <p class="mt-1">{{ orcamento.versao }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Moeda</p>
                                    <p class="mt-1">{{ orcamento.moeda }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Validade</p>
                                    <p class="mt-1" :class="{ 'text-red-600': isExpired }">
                                        {{ orcamento.validade ? formatDate(orcamento.validade) : 'Não definida' }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="orcamento.observacoes" class="mt-6">
                                <p class="text-sm font-medium text-gray-500">Observações</p>
                                <p class="mt-1 whitespace-pre-wrap text-gray-900">{{ orcamento.observacoes }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Itens do Orçamento -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Itens do Orçamento</CardTitle>
                            <CardDescription>{{ orcamento.itens.length }} item(s)</CardDescription>
                        </CardHeader>
                        <CardContent class="p-0">
                            <DataTable :value="orcamento.itens" responsive-layout="scroll" class="p-datatable-sm">
                                <Column field="descricao" header="Descrição">
                                    <template #body="{ data }">
                                        <div>
                                            <p class="font-medium">{{ data.descricao }}</p>
                                            <div class="mt-1 flex flex-wrap gap-2">
                                                <Badge v-if="data.eh_servico" variant="default" size="sm"> Serviço </Badge>
                                                <Badge v-if="data.servico" variant="outline" size="sm">
                                                    {{ data.servico.nome }}
                                                </Badge>
                                                <Badge v-if="data.servico_item" variant="outline" size="sm">
                                                    {{ data.servico_item.descricao_item }}
                                                </Badge>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="quantidade" header="Qtd" class="w-24">
                                    <template #body="{ data }">
                                        <div class="text-center">
                                            <p>{{ parseFloat(data.quantidade).toLocaleString('pt-BR') }}</p>
                                            <p v-if="data.unidade" class="text-xs text-gray-500">{{ data.unidade.codigo }}</p>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="preco_unitario" header="Preço Unit." class="w-32">
                                    <template #body="{ data }">
                                        <p class="text-right">{{ formatCurrency(data.preco_unitario) }}</p>
                                    </template>
                                </Column>

                                <Column field="total" header="Total" class="w-32">
                                    <template #body="{ data }">
                                        <p class="text-right font-medium">{{ formatCurrency(data.total) }}</p>
                                    </template>
                                </Column>
                            </DataTable>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Informações do Cliente -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Cliente</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ orcamento.cliente.nome }}</p>
                                    <p v-if="orcamento.cliente.documento" class="text-sm text-gray-500">{{ orcamento.cliente.documento }}</p>
                                </div>

                                <div v-if="orcamento.cliente.email">
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-sm">{{ orcamento.cliente.email }}</p>
                                </div>

                                <div v-if="orcamento.cliente.telefone">
                                    <p class="text-sm font-medium text-gray-500">Telefone</p>
                                    <p class="text-sm">{{ orcamento.cliente.telefone }}</p>
                                </div>

                                <div v-if="orcamento.cliente.endereco">
                                    <p class="text-sm font-medium text-gray-500">Endereço</p>
                                    <div class="space-y-1 text-sm">
                                        <p>{{ orcamento.cliente.endereco.linha1 }}</p>
                                        <p v-if="orcamento.cliente.endereco.linha2">{{ orcamento.cliente.endereco.linha2 }}</p>
                                        <p>{{ orcamento.cliente.endereco.cidade }}, {{ orcamento.cliente.endereco.estado }}</p>
                                        <p v-if="orcamento.cliente.endereco.cep">CEP: {{ orcamento.cliente.endereco.cep }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Resumo Financeiro -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Resumo Financeiro</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Subtotal:</span>
                                    <span class="text-sm">{{ formatCurrency(orcamento.subtotal) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Desconto:</span>
                                    <span class="text-sm text-red-600">- {{ formatCurrency(orcamento.desconto) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Impostos:</span>
                                    <span class="text-sm">+ {{ formatCurrency(orcamento.impostos) }}</span>
                                </div>

                                <hr />

                                <div class="flex justify-between">
                                    <span class="font-medium">Total:</span>
                                    <span class="text-lg font-bold">{{ formatCurrency(orcamento.total) }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Ações -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Ações</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <Button class="w-full" variant="outline">
                                <Download class="mr-2 h-4 w-4" />
                                Exportar PDF
                            </Button>

                            <Button class="w-full" variant="outline">
                                <Send class="mr-2 h-4 w-4" />
                                Enviar por Email
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
