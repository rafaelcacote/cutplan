<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, Edit, Eye, FileText, Filter, Plus, Printer, Search, Trash2, X } from 'lucide-vue-next';
import Calendar from 'primevue/calendar';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { computed, onMounted, ref, watch } from 'vue';

interface Cliente {
    id: number;
    nome: string;
    documento?: string;
    email?: string;
}

interface User {
    id: number;
    name: string;
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
    created_at: string;
    updated_at: string;
}

interface Props {
    orcamentos: {
        data: Orcamento[];
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
    filters: {
        cliente?: string;
        status?: string;
        data_inicio?: string;
        data_fim?: string;
        sort?: string;
        order?: string;
        per_page?: number;
    };
    statusOptions: Array<{
        label: string;
        value: string;
    }>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Orçamentos',
        href: '/orcamentos',
    },
];

// Estados dos filtros
const cliente = ref(props.filters?.cliente || '');
const status = ref(props.filters?.status || '');
const dataInicio = ref(props.filters?.data_inicio ? new Date(props.filters.data_inicio) : null);
const dataFim = ref(props.filters?.data_fim ? new Date(props.filters.data_fim) : null);
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);

// Estados da tabela
const loading = ref(false);
const showDeleteDialog = ref(false);
const orcamentoToDelete = ref<Orcamento | null>(null);

// Opções para dropdowns
const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 },
];

const askDeleteOrcamento = (orcamento: Orcamento) => {
    orcamentoToDelete.value = orcamento;
    showDeleteDialog.value = true;
};

// Funções para PDF
const visualizarPdf = (orcamento: Orcamento) => {
    window.open(route('orcamentos.visualizar-pdf', orcamento.id), '_blank');
};

const imprimirPdf = (orcamento: Orcamento) => {
    window.open(route('orcamentos.pdf', orcamento.id), '_blank');
};

const toast = useToast();
const page = usePage();

const showToastMessages = () => {
    // Exibe toast de sucesso
    const successMsg = (page.props.flash as any)?.success;
    if (successMsg) {
        toast.add({
            severity: 'success',
            summary: 'Sucesso',
            detail: successMsg,
            life: 4000,
            group: 'main',
            style: {
                background: '#22C55E',
                color: '#fff',
                borderRadius: '0.75rem',
                boxShadow: '0 4px 24px 0 rgba(34,197,94,0.15)',
            },
            icon: 'pi pi-check',
            contentClass: 'flex items-center gap-2',
            class: 'fade-in',
        });
    }
    // Exibe toast de erro
    const errorMsg = (page.props.flash as any)?.error;
    if (errorMsg) {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: errorMsg,
            life: 4000,
            group: 'main',
            style: {
                background: '#EF4444',
                color: '#fff',
                borderRadius: '0.75rem',
                boxShadow: '0 4px 24px 0 rgba(239,68,68,0.15)',
            },
            icon: 'pi pi-exclamation-triangle',
            contentClass: 'flex items-center gap-2',
            class: 'fade-in',
        });
    }
};

onMounted(() => {
    showToastMessages();
});

watch(
    () => page.props.flash,
    () => {
        showToastMessages();
    },
    { deep: true },
);

const confirmDeleteOrcamento = () => {
    if (orcamentoToDelete.value) {
        router.delete(`/orcamentos/${orcamentoToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                orcamentoToDelete.value = null;
            },
        });
    }
};

// Funções de filtros e paginação
const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (cliente.value) params.cliente = cliente.value;
    if (status.value) params.status = status.value;
    if (dataInicio.value) params.data_inicio = dataInicio.value.toISOString().split('T')[0];
    if (dataFim.value) params.data_fim = dataFim.value.toISOString().split('T')[0];
    if (perPage.value !== 10) params.per_page = perPage.value;

    router.get('/orcamentos', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

const clearFilters = () => {
    cliente.value = '';
    status.value = '';
    dataInicio.value = null;
    dataFim.value = null;
    perPage.value = 10;
    updateFilters();
};

const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (cliente.value) params.cliente = cliente.value;
    if (status.value) params.status = status.value;
    if (dataInicio.value) params.data_inicio = dataInicio.value.toISOString().split('T')[0];
    if (dataFim.value) params.data_fim = dataFim.value.toISOString().split('T')[0];
    if (perPage.value !== 10) params.per_page = perPage.value;

    router.get('/orcamentos', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

// Funções auxiliares
const formatCurrency = (value: string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(parseFloat(value));
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
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

const hasActiveFilters = computed(() => {
    return cliente.value || status.value || dataInicio.value || dataFim.value;
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Orçamentos" />

        <Toast position="top-center" group="main" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Orçamentos</h1>
                    <p class="mt-1 text-gray-600">Gerencie todos os orçamentos da empresa</p>
                </div>
                <div class="flex gap-2">
                    <Link
                        :href="`/orcamentos/create-simple`"
                        class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:outline-none"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Orçamento (Simples)
                    </Link>
                    <Link
                        :href="`/orcamentos/create`"
                        class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Orçamento
                    </Link>
                </div>
            </div>

            <!-- Filtros -->
            <Card>
                <CardHeader class="pb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Filter class="h-5 w-5 text-gray-500" />
                            <CardTitle class="text-lg">Filtros</CardTitle>
                            <span
                                v-if="hasActiveFilters"
                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                            >
                                {{ [cliente, status, dataInicio, dataFim].filter(Boolean).length }} ativo(s)
                            </span>
                        </div>
                        <Button variant="ghost" size="sm" @click="showFilters = !showFilters" class="text-gray-600">
                            <ChevronDown :class="['h-4 w-4 transition-transform', showFilters && 'rotate-180']" />
                        </Button>
                    </div>
                </CardHeader>
                <CardContent v-if="showFilters" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div class="space-y-2">
                            <Label for="filter-cliente">Cliente</Label>
                            <InputText id="filter-cliente" v-model="cliente" placeholder="Nome do cliente..." class="w-full" />
                        </div>

                        <div class="space-y-2">
                            <Label for="filter-status">Status</Label>
                            <Dropdown
                                id="filter-status"
                                v-model="status"
                                :options="[{ label: 'Todos', value: '' }, ...props.statusOptions]"
                                option-label="label"
                                option-value="value"
                                placeholder="Selecione o status"
                                class="w-full"
                            />
                        </div>

                        <div class="space-y-2">
                            <Label for="filter-data-inicio">Data Início</Label>
                            <Calendar
                                id="filter-data-inicio"
                                v-model="dataInicio"
                                date-format="dd/mm/yy"
                                placeholder="dd/mm/aaaa"
                                class="w-full"
                                show-icon
                            />
                        </div>

                        <div class="space-y-2">
                            <Label for="filter-data-fim">Data Fim</Label>
                            <Calendar
                                id="filter-data-fim"
                                v-model="dataFim"
                                date-format="dd/mm/yy"
                                placeholder="dd/mm/aaaa"
                                class="w-full"
                                show-icon
                            />
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 border-t pt-4 sm:flex-row">
                        <Button @click="updateFilters" class="flex-1 sm:flex-none">
                            <Search class="mr-2 h-4 w-4" />
                            Filtrar
                        </Button>
                        <Button variant="outline" @click="clearFilters" class="flex-1 sm:flex-none">
                            <X class="mr-2 h-4 w-4" />
                            Limpar
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Tabela -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <CardTitle>Lista de Orçamentos</CardTitle>
                            <CardDescription> {{ props.orcamentos.total }} orçamento(s) encontrado(s) </CardDescription>
                        </div>
                        <div class="flex items-center gap-2">
                            <Label for="per-page" class="text-sm">Exibir:</Label>
                            <Dropdown
                                id="per-page"
                                v-model="perPage"
                                :options="perPageOptions"
                                option-label="label"
                                option-value="value"
                                @change="updateFilters"
                                class="w-32"
                            />
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <DataTable :value="props.orcamentos.data" :loading="loading" striped-rows responsive-layout="scroll" class="p-datatable-sm">
                        <Column field="id" header="#" class="w-16">
                            <template #body="{ data }">
                                <span class="font-mono text-sm">#{{ data.id }}</span>
                            </template>
                        </Column>

                        <Column field="cliente.nome" header="Cliente">
                            <template #body="{ data }">
                                <div>
                                    <p class="font-medium">{{ data.cliente.nome }}</p>
                                    <p v-if="data.cliente.documento" class="text-sm text-gray-500">{{ data.cliente.documento }}</p>
                                </div>
                            </template>
                        </Column>

                        <Column field="status" header="Status" class="w-32">
                            <template #body="{ data }">
                                <Tag :value="data.status_label" :severity="getStatusSeverity(data.status)" class="text-xs" />
                            </template>
                        </Column>

                        <Column field="total" header="Total" class="w-32">
                            <template #body="{ data }">
                                <span class="font-medium">{{ formatCurrency(data.total) }}</span>
                            </template>
                        </Column>

                        <Column field="validade" header="Validade" class="w-32">
                            <template #body="{ data }">
                                <span v-if="data.validade" class="text-sm">{{ formatDate(data.validade) }}</span>
                                <span v-else class="text-sm text-gray-400">-</span>
                            </template>
                        </Column>

                        <Column field="created_at" header="Criado em" class="w-32">
                            <template #body="{ data }">
                                <span class="text-sm text-gray-600">{{ formatDate(data.created_at) }}</span>
                            </template>
                        </Column>

                        <Column header="Ações" class="w-32">
                            <template #body="{ data }">
                                <div class="flex items-center gap-1">
                                    <Link
                                        :href="`/orcamentos/${data.id}`"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-blue-600 transition-colors hover:bg-blue-50"
                                        title="Visualizar"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                    <Link
                                        :href="`/orcamentos/${data.id}/edit`"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md text-amber-600 transition-colors hover:bg-amber-50"
                                        title="Editar"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="visualizarPdf(data)"
                                        class="h-8 w-8 p-0 text-green-600 hover:bg-green-50"
                                        title="Visualizar PDF"
                                    >
                                        <FileText class="h-4 w-4" />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="imprimirPdf(data)"
                                        class="h-8 w-8 p-0 text-purple-600 hover:bg-purple-50"
                                        title="Imprimir PDF"
                                    >
                                        <Printer class="h-4 w-4" />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="askDeleteOrcamento(data)"
                                        class="h-8 w-8 p-0 text-red-600 hover:bg-red-50"
                                        title="Excluir"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </CardContent>
            </Card>

            <!-- Paginação -->
            <div v-if="props.orcamentos.last_page > 1" class="flex items-center justify-between">
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ (props.orcamentos.current_page - 1) * props.orcamentos.per_page + 1 }}</span>
                    até
                    <span class="font-medium">{{ Math.min(props.orcamentos.current_page * props.orcamentos.per_page, props.orcamentos.total) }}</span>
                    de
                    <span class="font-medium">{{ props.orcamentos.total }}</span>
                    resultados
                </p>

                <nav class="flex items-center gap-2">
                    <Button
                        v-for="link in props.orcamentos.links"
                        :key="link.label"
                        :variant="link.active ? 'default' : 'outline'"
                        :disabled="!link.url"
                        size="sm"
                        @click="link.url && goToPage(parseInt(link.url.split('page=')[1] || '1'))"
                        v-html="link.label"
                        class="min-w-[2.5rem]"
                    />
                </nav>
            </div>
        </div>

        <!-- Dialog de Confirmação de Exclusão -->
        <Dialog v-model:visible="showDeleteDialog" modal header="Confirmar Exclusão" class="w-full max-w-md">
            <div class="space-y-4">
                <p class="text-gray-700">
                    Tem certeza que deseja excluir o orçamento
                    <strong v-if="orcamentoToDelete">#{{ orcamentoToDelete.id }}</strong
                    >?
                </p>
                <p class="text-sm text-gray-500">Esta ação não pode ser desfeita.</p>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button variant="outline" @click="showDeleteDialog = false" :disabled="loading"> Cancelar </Button>
                    <Button variant="destructive" @click="confirmDeleteOrcamento" :disabled="loading"> Excluir </Button>
                </div>
            </template>
        </Dialog>
    </AppLayout>
</template>
