
<script setup lang="ts">

function formatEstoqueMinimo(value?: string | number) {
    if (!value) return '-';
    return parseInt(Number(value).toString(), 10);
}
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, Edit, Eye, Filter, Plus, Trash2, X } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, watch } from 'vue';
import TagAtivo from './_TagAtivo.vue';

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
    created_at: string;
    updated_at: string;
    tipo?: { id: number; nome: string };
    unidade?: { id: number; nome: string };
    fornecedorPadrao?: { id: number; nome: string };
}

interface Props {
    materiais: {
        data: Material[];
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
        nome?: string;
        sku?: string;
        sort?: string;
        order?: string;
        per_page?: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Materiais', href: '/materiais' },
];

const nome = ref(props.filters?.nome || '');
const sku = ref(props.filters?.sku || '');
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);
const loading = ref(false);
const showDeleteDialog = ref(false);
const materialToDelete = ref<Material | null>(null);

const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 },
];

const askDeleteMaterial = (material: Material) => {
    materialToDelete.value = material;
    showDeleteDialog.value = true;
};

const toast = useToast();
const page = usePage();

const showToastMessages = () => {
    const successMsg = (page.props.flash as any)?.success;
    if (successMsg) {
        toast.add({
            severity: 'success',
            summary: 'Sucesso',
            detail: successMsg,
            life: 4000,
            group: 'main',
            icon: 'pi pi-check',
            contentClass: 'flex items-center gap-2',
            class: 'fade-in',
        });
    }
    const errorMsg = (page.props.flash as any)?.error;
    if (errorMsg) {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: errorMsg,
            life: 4000,
            group: 'main',
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

const confirmDeleteMaterial = () => {
    if (materialToDelete.value) {
        router.delete(`/materiais/${materialToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                materialToDelete.value = null;
            },
        });
    }
};

const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (nome.value) params.nome = nome.value;
    if (sku.value) params.sku = sku.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/materiais', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

const clearFilters = () => {
    nome.value = '';
    sku.value = '';
    perPage.value = 10;
    updateFilters();
};

const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (nome.value) params.nome = nome.value;
    if (sku.value) params.sku = sku.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/materiais', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

let nomeTimeout: ReturnType<typeof setTimeout>;
watch(nome, () => {
    clearTimeout(nomeTimeout);
    nomeTimeout = setTimeout(() => {
        updateFilters();
    }, 500);
});

let skuTimeout: ReturnType<typeof setTimeout>;
watch(sku, () => {
    clearTimeout(skuTimeout);
    skuTimeout = setTimeout(() => {
        updateFilters();
    }, 500);
});

watch(perPage, () => {
    updateFilters();
});

const formatCurrency = (value?: string) => {
    if (!value) return '-';
    return Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
};

const formatBoolean = (val: boolean) => (val ? 'Sim' : 'Não');
</script>

<template>
    <Head title="Materiais" />
    <Toast position="top-right" group="main" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Materiais</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie seus materiais</p>
                </div>
                <Button
                    as-child
                    class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                >
                    <Link :href="route('materiais.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Material
                    </Link>
                </Button>
            </div>
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle class="flex items-center gap-2 text-[#3B82F6] dark:text-[#60A5FA]">
                            <Filter class="h-5 w-5" />
                            Filtros
                        </CardTitle>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="showFilters = !showFilters"
                            class="rounded-full text-[#64748B] transition-all duration-300 hover:bg-[#F1F5F9] dark:text-[#CBD5E1] dark:hover:bg-[#334155]"
                        >
                            <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': showFilters }" />
                        </Button>
                    </div>
                </CardHeader>
                <transition name="fade">
                    <CardContent v-if="showFilters" class="pt-0">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <div class="space-y-2">
                                <Label for="nome">Nome</Label>
                                <InputText id="nome" v-model="nome" placeholder="Buscar por nome" class="w-full" />
                            </div>
                            <div class="space-y-2">
                                <Label for="sku">SKU</Label>
                                <InputText id="sku" v-model="sku" placeholder="Buscar por SKU" class="w-full" />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="clearFilters"
                                v-if="nome || sku || perPage !== 10"
                                class="rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                <X class="mr-2 h-4 w-4" />
                                Limpar Filtros
                            </Button>
                        </div>
                    </CardContent>
                </transition>
            </Card>
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Lista de Materiais</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
                        Mostrando {{ materiais.data.length }} de {{ materiais.total }} materiais
                        <span v-if="nome || sku" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="relative">
                        <transition name="fade">
                            <div
                                v-if="loading"
                                class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-[#F8FAFC]/80 dark:bg-[#1E293B]/80"
                            >
                                <div class="w-full">
                                    <div class="mb-2 h-6 animate-pulse rounded bg-[#E0E7EF] dark:bg-[#334155]" v-for="i in 8" :key="i"></div>
                                </div>
                            </div>
                        </transition>
                        <DataTable
                            :value="materiais.data"
                            class="p-datatable-sm striped highlight-on-hover premium-table w-full"
                            responsiveLayout="scroll"
                            :loading="loading"
                            stripedRows
                            rowHover
                        >
                            <Column field="sku" header="SKU" sortable>
                                <template #body="{ data }">
                                    <div class="font-mono text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ data.sku }}</div>
                                </template>
                            </Column>
                            <Column field="nome" header="Nome" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.nome }}</div>
                                </template>
                            </Column>
                            <Column field="tipo.nome" header="Tipo" sortable>
                                <template #body="{ data }">
                                    <span>{{ data.tipo?.nome || '-' }}</span>
                                </template>
                            </Column>
                            <Column field="unidade.nome" header="Unidade" sortable>
                                <template #body="{ data }">
                                    <span>{{ data.unidade?.nome || '-' }}</span>
                                </template>
                            </Column>
                            <Column field="fornecedorPadrao.nome" header="Fornecedor Padrão">
                                <template #body="{ data }">
                                    <span>{{ data.fornecedorPadrao?.nome || '-' }}</span>
                                </template>
                            </Column>
                            <Column field="preco_custo" header="Preço de Custo">
                                <template #body="{ data }">
                                    <span>{{ formatCurrency(data.preco_custo) }}</span>
                                </template>
                            </Column>
                            <Column field="estoque_minimo" header="Estoque Mínimo">
                                    <template #body="{ data }">
                                        <span>{{ formatEstoqueMinimo(data.estoque_minimo) }}</span>
                                    </template>
                            </Column>
                            <Column field="controla_estoque" header="Controla Estoque">
                                <template #body="{ data }">
                                    <span>{{ formatBoolean(data.controla_estoque) }}</span>
                                </template>
                            </Column>
                            <Column field="ativo" header="Ativo">
                                <template #body="{ data }">
                                    <TagAtivo :ativo="data.ativo">{{ formatBoolean(data.ativo) }}</TagAtivo>
                                </template>
                            </Column>
                            <Column header="Ações" style="width: 180px">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                            class="bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] transition-transform duration-300 hover:scale-105 dark:text-[#60A5FA]"
                                        >
                                            <Link :href="route('materiais.show', { materiai: data.id })">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                            class="bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] transition-transform duration-300 hover:scale-105 dark:text-[#A5B4FC]"
                                        >
                                            <Link :href="route('materiais.edit', { materiai: data.id })">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="text-red-500 transition-all duration-300 hover:scale-105 hover:bg-red-500/80 hover:text-white"
                                            @click="askDeleteMaterial(data)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </template>
                            </Column>
                            <template #empty>
                                <div class="py-8 text-center">
                                    <p class="mb-4 text-[#64748B] dark:text-[#CBD5E1]">
                                        {{ nome || sku ? 'Nenhum material encontrado com os filtros aplicados.' : 'Nenhum material encontrado.' }}
                                    </p>
                                    <Button
                                        as-child
                                        v-if="!nome && !sku"
                                        class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                                    >
                                        <Link :href="route('materiais.create')">
                                            <Plus class="mr-2 h-4 w-4" />
                                            Novo Material
                                        </Link>
                                    </Button>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </CardContent>
            </Card>
            <Dialog v-model:visible="showDeleteDialog" modal header="Remover Material" :closable="false" :style="{ width: '400px' }">
                <div class="py-4">
                    <p class="mb-4">
                        Tem certeza que deseja remover o material <span class="font-bold">{{ materialToDelete?.nome }}</span
                        >?
                    </p>
                    <div class="flex justify-end gap-2">
                        <Button variant="outline" @click="showDeleteDialog = false">Cancelar</Button>
                        <Button variant="destructive" @click="confirmDeleteMaterial">Remover</Button>
                    </div>
                </div>
            </Dialog>
        </div>
    </AppLayout>
</template>
