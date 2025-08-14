<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, Edit, Eye, Filter, Plus, Trash2, X } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, watch } from 'vue';

interface ServicoItem {
    id: number;
    descricao_item: string;
    ordem?: number;
    opcional: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    servicoItens: {
        data: ServicoItem[];
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
        descricao_item?: string;
        opcional?: string;
        sort?: string;
        order?: string;
        per_page?: number;
    };
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
];

// Estados dos filtros
const descricaoItem = ref(props.filters?.descricao_item || '');

const opcional = ref(props.filters?.opcional || '');
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);

// Estados da tabela
const loading = ref(false);
const showDeleteDialog = ref(false);
const servicoItemToDelete = ref<ServicoItem | null>(null);

// Opções para dropdowns
const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 },
];

const opcionalOptions = [
    { label: 'Todos', value: '' },
    { label: 'Obrigatório', value: '0' },
    { label: 'Opcional', value: '1' },
];

const askDeleteServicoItem = (servicoItem: ServicoItem) => {
    servicoItemToDelete.value = servicoItem;
    showDeleteDialog.value = true;
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

// Observa mudanças nas flash messages
watch(
    () => page.props.flash,
    () => {
        showToastMessages();
    },
    { deep: true },
);

const confirmDeleteServicoItem = () => {
    if (servicoItemToDelete.value) {
        router.delete(`/servico-itens/${servicoItemToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                servicoItemToDelete.value = null;
            },
        });
    }
};

// Funções de filtros e paginação
const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (descricaoItem.value) params.descricao_item = descricaoItem.value;
    // filtro por servico_nome removido
    if (opcional.value !== '') params.opcional = opcional.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/servico-itens', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

const clearFilters = () => {
    descricaoItem.value = '';

    opcional.value = '';
    perPage.value = 10;
    updateFilters();
};

const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (descricaoItem.value) params.descricao_item = descricaoItem.value;

    if (opcional.value !== '') params.opcional = opcional.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/servico-itens', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};

// Debounce para busca
let descricaoTimeout: ReturnType<typeof setTimeout>;
watch(descricaoItem, () => {
    clearTimeout(descricaoTimeout);
    descricaoTimeout = setTimeout(() => {
        updateFilters();
    }, 500);
});

watch(opcional, () => {
    updateFilters();
});

watch(perPage, () => {
    updateFilters();
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR');
};
</script>

<template>
    <Head title="Itens de Serviço" />
    <Toast position="top-right" group="main" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Itens de Serviço</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie os itens dos seus serviços</p>
                </div>
                <Button
                    as-child
                    class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                >
                    <Link :href="route('servico-itens.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Item
                    </Link>
                </Button>
            </div>

            <!-- Filtros -->
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
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="space-y-2">
                                <Label for="descricao_item">Descrição</Label>
                                <InputText
                                    id="descricao_item"
                                    v-model="descricaoItem"
                                    placeholder="Buscar por descrição"
                                    class="w-full"
                                    :class="{ 'border-[#3B82F6] ring-2 ring-[#3B82F6]/20': descricaoItem }"
                                />
                            </div>
                            <!-- Campo de busca por serviço removido -->
                            <div class="space-y-2">
                                <Label for="opcional">Tipo</Label>
                                <Dropdown
                                    id="opcional"
                                    v-model="opcional"
                                    :options="opcionalOptions"
                                    option-label="label"
                                    option-value="value"
                                    class="w-full"
                                    :class="{ 'border-[#3B82F6] ring-2 ring-[#3B82F6]/20': opcional !== '' }"
                                />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="clearFilters"
                                v-if="descricaoItem || opcional !== '' || perPage !== 10"
                                class="rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                <X class="mr-2 h-4 w-4" />
                                Limpar Filtros
                            </Button>
                        </div>
                    </CardContent>
                </transition>
            </Card>

            <!-- Tabela -->
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Lista de Itens de Serviço</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
                        Mostrando {{ servicoItens.data.length }} de {{ servicoItens.total }} itens
                        <span v-if="descricaoItem || opcional !== ''" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="relative">
                        <!-- Loading overlay -->
                        <transition name="fade">
                            <div
                                v-if="loading"
                                class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-white/95 backdrop-blur-sm dark:bg-[#1E293B]/95"
                            >
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-8 w-8 animate-spin rounded-full border-3 border-[#3B82F6] border-t-transparent"></div>
                                    <p class="text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">Carregando itens...</p>
                                </div>
                            </div>
                        </transition>

                        <DataTable
                            :value="servicoItens.data"
                            class="p-datatable-sm striped highlight-on-hover premium-table w-full"
                            responsiveLayout="scroll"
                            :loading="loading"
                            stripedRows
                            rowHover
                        >
                            <Column field="ordem" header="Ordem" sortable style="width: 80px">
                                <template #body="{ data }">
                                    <div class="text-center">
                                        <span
                                            v-if="data.ordem"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#3B82F6] text-sm font-bold text-white"
                                        >
                                            {{ data.ordem }}
                                        </span>
                                        <span v-else class="text-[#CBD5E1] dark:text-[#64748B]">-</span>
                                    </div>
                                </template>
                            </Column>
                            <Column field="descricao_item" header="Descrição" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.descricao_item }}</div>
                                </template>
                            </Column>
                            <!-- Coluna de serviço removida -->
                            <Column field="opcional" header="Tipo" sortable style="width: 120px">
                                <template #body="{ data }">
                                    <Tag
                                        :value="data.opcional ? 'Opcional' : 'Obrigatório'"
                                        :severity="data.opcional ? 'info' : 'success'"
                                        class="font-semibold"
                                    />
                                </template>
                            </Column>
                            <Column field="created_at" header="Criado em" sortable>
                                <template #body="{ data }">
                                    <span class="text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ formatDate(data.created_at) }}</span>
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
                                            <Link :href="route('servico-itens.show', data.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                            class="bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] transition-transform duration-300 hover:scale-105 dark:text-[#A5B4FC]"
                                        >
                                            <Link :href="route('servico-itens.edit', data.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="text-red-500 transition-all duration-300 hover:scale-105 hover:bg-red-500/80 hover:text-white"
                                            @click="askDeleteServicoItem(data)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </template>
                            </Column>
                            <template #empty>
                                <div class="py-8 text-center">
                                    <p class="mb-4 text-[#64748B] dark:text-[#CBD5E1]">
                                        {{
                                            descricaoItem || opcional !== ''
                                                ? 'Nenhum item encontrado com os filtros aplicados.'
                                                : 'Nenhum item de serviço encontrado.'
                                        }}
                                    </p>
                                    <Button
                                        as-child
                                        v-if="!descricaoItem && opcional === ''"
                                        class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                                    >
                                        <Link href="/servico-itens/create">
                                            <Plus class="mr-2 h-4 w-4" />
                                            Criar primeiro item
                                        </Link>
                                    </Button>
                                    <Button
                                        v-else
                                        variant="outline"
                                        @click="clearFilters"
                                        class="mr-2 rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                                    >
                                        <X class="mr-2 h-4 w-4" />
                                        Limpar Filtros
                                    </Button>
                                </div>
                            </template>
                        </DataTable>
                    </div>

                    <!-- Paginação customizada -->
                    <div v-if="servicoItens.last_page > 1" class="mt-6 flex flex-col items-center justify-between gap-4 md:flex-row">
                        <div class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
                            Mostrando {{ (servicoItens.current_page - 1) * servicoItens.per_page + 1 }} a
                            {{ Math.min(servicoItens.current_page * servicoItens.per_page, servicoItens.total) }}
                            de {{ servicoItens.total }} resultados
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Botão Anterior -->
                            <Button
                                variant="outline"
                                size="sm"
                                @click="goToPage(servicoItens.current_page - 1)"
                                :disabled="servicoItens.current_page === 1 || loading"
                                class="rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                Anterior
                            </Button>
                            <!-- Números das páginas -->
                            <div class="flex items-center gap-1">
                                <!-- Primeira página -->
                                <Button
                                    v-if="servicoItens.current_page > 3"
                                    variant="ghost"
                                    size="sm"
                                    @click="goToPage(1)"
                                    :disabled="loading"
                                    class="transition-transform duration-300 hover:scale-105"
                                >
                                    1
                                </Button>
                                <!-- Reticências início -->
                                <span v-if="servicoItens.current_page > 4" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                                <!-- Páginas ao redor da atual -->
                                <template
                                    v-for="page in Array.from({ length: Math.min(5, servicoItens.last_page) }, (_, i) => {
                                        const start = Math.max(1, Math.min(servicoItens.current_page - 2, servicoItens.last_page - 4));
                                        return start + i;
                                    }).filter((p) => p <= servicoItens.last_page)"
                                    :key="page"
                                >
                                    <Button
                                        :variant="page === servicoItens.current_page ? 'default' : 'ghost'"
                                        size="sm"
                                        @click="goToPage(page)"
                                        :disabled="loading"
                                        class="transition-transform duration-300 hover:scale-105"
                                    >
                                        {{ page }}
                                    </Button>
                                </template>
                                <!-- Reticências fim -->
                                <span v-if="servicoItens.current_page < servicoItens.last_page - 3" class="px-2 text-[#CBD5E1] dark:text-[#64748B]"
                                    >...</span
                                >
                                <!-- Última página -->
                                <Button
                                    v-if="servicoItens.current_page < servicoItens.last_page - 2"
                                    variant="ghost"
                                    size="sm"
                                    @click="goToPage(servicoItens.last_page)"
                                    :disabled="loading"
                                    class="transition-transform duration-300 hover:scale-105"
                                >
                                    {{ servicoItens.last_page }}
                                </Button>
                            </div>
                            <!-- Botão Próximo -->
                            <Button
                                variant="outline"
                                size="sm"
                                @click="goToPage(servicoItens.current_page + 1)"
                                :disabled="servicoItens.current_page === servicoItens.last_page || loading"
                                class="rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                Próximo
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Modal de confirmação de exclusão -->
            <Dialog v-model:visible="showDeleteDialog" modal :closable="false" class="mx-auto w-full max-w-md">
                <template #container="{ closeCallback }">
                    <div
                        class="animate-fade-in space-y-8 rounded-2xl border border-red-200 bg-white p-8 shadow-2xl transition-all duration-300 dark:border-red-800 dark:bg-[#1E293B]"
                    >
                        <!-- Header com ícone animado -->
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-14 w-14 flex-shrink-0 animate-pulse items-center justify-center rounded-full bg-red-100 dark:bg-red-900"
                            >
                                <Trash2 class="h-7 w-7 text-red-500" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-red-500">Excluir Item</h3>
                                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
                                    Esta ação <span class="font-semibold text-red-500">não pode ser desfeita</span>.
                                </p>
                            </div>
                        </div>
                        <!-- Conteúdo -->
                        <div class="space-y-4">
                            <p class="text-lg text-[#1E293B] dark:text-white">
                                Tem certeza que deseja excluir o item
                                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ servicoItemToDelete?.descricao_item }}</span
                                >?
                            </p>
                            <div class="flex items-center gap-3 rounded-md border-l-4 border-red-500 bg-red-50 p-4 dark:bg-red-900">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"
                                    />
                                </svg>
                                <span class="text-sm font-medium text-red-500"
                                    >Todos os dados relacionados a este item serão <u>permanentemente removidos</u>.</span
                                >
                            </div>
                        </div>
                        <!-- Ações -->
                        <div class="flex justify-end gap-4 border-t border-[#E0E7EF] pt-6 dark:border-[#334155]">
                            <Button
                                variant="outline"
                                @click="showDeleteDialog = false"
                                class="rounded-lg px-7 py-2 text-base transition-all hover:bg-[#F1F5F9] dark:hover:bg-[#334155]"
                            >
                                Cancelar
                            </Button>
                            <Button
                                variant="destructive"
                                @click="confirmDeleteServicoItem"
                                class="flex items-center gap-2 rounded-lg px-7 py-2 text-base shadow transition-all hover:bg-red-600 hover:text-white"
                            >
                                <Trash2 class="h-5 w-5" />
                                <span>Excluir</span>
                            </Button>
                        </div>
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>

<style scoped>
.premium-table .p-datatable-tbody > tr {
    transition: background 0.3s;
}
.premium-table .p-datatable-tbody > tr.p-row-odd {
    background: #f1f5f9;
}
.premium-table .p-datatable-tbody > tr.p-row-even {
    background: #fff;
}
.premium-table .p-datatable-tbody > tr:hover {
    background: #e0e7ef !important;
    cursor: pointer;
}
.dark .premium-table .p-datatable-tbody > tr.p-row-odd {
    background: #1e293b;
}
.dark .premium-table .p-datatable-tbody > tr.p-row-even {
    background: #334155;
}
.dark .premium-table .p-datatable-tbody > tr:hover {
    background: #334155 !important;
}
.p-button,
.p-dropdown,
.p-inputtext {
    border-radius: 0.75rem !important;
    transition: all 0.3s;
}
.p-button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 24px 0 rgba(59, 130, 246, 0.1);
}
.p-dropdown-panel .p-dropdown-items .p-dropdown-item.p-highlight {
    background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%) !important;
    color: #fff !important;
}
.p-toast {
    z-index: 9999;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.fade-in {
    animation: fadeIn 0.3s;
}
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
