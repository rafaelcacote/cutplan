<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, Edit, Eye, Filter, Plus, Trash2, X } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { computed, onMounted, ref, watch } from 'vue';

interface User {
    id: number;
    name: string;
}
interface Equipe {
    id: number;
    nome: string;
    tipo: string;
    lider_user_id?: number;
    lider?: User | null;
    membros_count?: number;
    created_at?: string;
    updated_at?: string;
}

const props = defineProps<{ equipes: Equipe[]; filters?: { nome?: string; tipo?: string } }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Equipes', href: '/equipes' },
];

// Filtros e estado
const nome = ref(props.filters?.nome ?? '');
const tipo = ref(props.filters?.tipo ?? '');
const showFilters = ref(false);
const loading = ref(false);

const toast = useToast();
const page = usePage();

const showToastMessages = () => {
    const successMsg = (page.props.flash as any)?.success;
    if (successMsg) {
        toast.add({ severity: 'success', summary: 'Sucesso', detail: successMsg, life: 3500, group: 'main' });
    }
    const errorMsg = (page.props.flash as any)?.error;
    if (errorMsg) {
        toast.add({ severity: 'error', summary: 'Erro', detail: errorMsg, life: 3500, group: 'main' });
    }
};

onMounted(() => showToastMessages());
watch(
    () => page.props.flash,
    () => showToastMessages(),
    { deep: true },
);

const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (nome.value) params.nome = nome.value;
    if (tipo.value) params.tipo = tipo.value;
    router.get('/equipes', params, {
        preserveState: true,
        replace: true,
        onFinish: () => (loading.value = false),
    });
};

let nomeTimeout: ReturnType<typeof setTimeout>;
watch(nome, () => {
    clearTimeout(nomeTimeout);
    nomeTimeout = setTimeout(updateFilters, 400);
});
let tipoTimeout: ReturnType<typeof setTimeout>;
watch(tipo, () => {
    clearTimeout(tipoTimeout);
    tipoTimeout = setTimeout(updateFilters, 400);
});

const clearFilters = () => {
    nome.value = '';
    tipo.value = '';
    updateFilters();
};

const showDeleteDialog = ref(false);
const equipeToDelete = ref<Equipe | null>(null);
const askDeleteEquipe = (equipe: Equipe) => {
    equipeToDelete.value = equipe;
    showDeleteDialog.value = true;
};
const confirmDeleteEquipe = () => {
    if (equipeToDelete.value) {
        router.delete(`/equipes/${equipeToDelete.value.id}`, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Equipe excluída', detail: 'Equipe excluída com sucesso!', life: 3000, group: 'main' });
                showDeleteDialog.value = false;
                equipeToDelete.value = null;
            },
        });
    }
};

// Usado no template
const equipes = computed(() => props.equipes);
</script>

<template>
    <Head title="Equipes" />
    <Toast position="top-right" group="main" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Equipes</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie suas equipes</p>
                </div>
                <Button
                    as-child
                    class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                >
                    <Link :href="route('equipes.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Nova Equipe
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
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="nome">Nome</label>
                                <InputText id="nome" v-model="nome" placeholder="Buscar por nome" class="w-full" />
                            </div>
                            <div class="space-y-2">
                                <label for="tipo">Tipo</label>
                                <InputText id="tipo" v-model="tipo" placeholder="Buscar por tipo" class="w-full" />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="clearFilters"
                                v-if="nome || tipo"
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
                    <CardTitle class="text-[#1E293B] dark:text-white">Lista de Equipes</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
                        Mostrando {{ equipes.length }} equipes
                        <span v-if="nome || tipo" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtradas)</span>
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
                            :value="equipes"
                            class="p-datatable-sm striped highlight-on-hover premium-table w-full"
                            responsiveLayout="scroll"
                            :loading="loading"
                            stripedRows
                            rowHover
                        >
                            <Column field="nome" header="Nome" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.nome }}</div>
                                </template>
                            </Column>
                            <Column field="tipo" header="Tipo" sortable>
                                <template #body="{ data }">
                                    <span class="text-[#64748B] dark:text-[#CBD5E1]">{{ data.tipo }}</span>
                                </template>
                            </Column>
                            <Column field="lider.name" header="Líder" sortable>
                                <template #body="{ data }">
                                    <span v-if="data.lider" class="font-medium text-[#3B82F6] dark:text-[#60A5FA]">{{
                                        (data.lider as any).name
                                    }}</span>
                                    <span v-else class="text-[#CBD5E1] dark:text-[#64748B]">-</span>
                                </template>
                            </Column>
                            <Column field="membros_count" header="Membros">
                                <template #body="{ data }">
                                    <span class="font-semibold">{{ data.membros_count ?? 0 }}</span>
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
                                            <Link :href="route('equipes.show', data.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            as-child
                                            class="bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] transition-transform duration-300 hover:scale-105 dark:text-[#A5B4FC]"
                                        >
                                            <Link :href="route('equipes.edit', data.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="text-red-500 transition-all duration-300 hover:scale-105 hover:bg-red-500/80 hover:text-white"
                                            @click="askDeleteEquipe(data)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                        <!-- Dialog de confirmação de exclusão de equipe -->
                                        <Dialog
                                            v-model:visible="showDeleteDialog"
                                            :modal="true"
                                            :closable="false"
                                            :style="{ width: '100%', maxWidth: '28rem' }"
                                        >
                                            <div
                                                class="animate-fade-in space-y-8 rounded-2xl border border-red-200 bg-white p-8 shadow-2xl transition-all duration-300 dark:border-red-800 dark:bg-[#1E293B]"
                                            >
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="flex h-14 w-14 flex-shrink-0 animate-pulse items-center justify-center rounded-full bg-red-100 dark:bg-red-900"
                                                    >
                                                        <Trash2 class="h-7 w-7 text-red-500" />
                                                    </div>
                                                    <div>
                                                        <h3 class="text-xl font-bold text-red-500">Excluir Equipe</h3>
                                                        <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
                                                            Esta ação <span class="font-semibold text-red-500">não pode ser desfeita</span>.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="space-y-4">
                                                    <p class="text-lg text-[#1E293B] dark:text-white">
                                                        Tem certeza que deseja excluir a equipe
                                                        <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{
                                                            equipeToDelete?.nome
                                                        }}</span
                                                        >?
                                                    </p>
                                                    <div
                                                        class="flex items-center gap-3 rounded-md border-l-4 border-red-500 bg-red-50 p-4 dark:bg-red-900"
                                                    >
                                                        <svg
                                                            class="h-5 w-5 text-red-500"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"
                                                            />
                                                        </svg>
                                                        <span class="text-sm font-medium text-red-500"
                                                            >Todos os dados relacionados a esta equipe serão <u>permanentemente removidos</u>.</span
                                                        >
                                                    </div>
                                                </div>
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
                                                        @click="confirmDeleteEquipe"
                                                        class="flex items-center gap-2 rounded-lg px-7 py-2 text-base shadow transition-all hover:bg-red-600 hover:text-white"
                                                    >
                                                        <Trash2 class="h-5 w-5" />
                                                        <span>Excluir</span>
                                                    </Button>
                                                </div>
                                            </div>
                                        </Dialog>
                                    </div>
                                </template>
                            </Column>
                            <template #empty>
                                <div class="py-8 text-center">
                                    <p class="mb-4 text-[#64748B] dark:text-[#CBD5E1]">
                                        {{ nome || tipo ? 'Nenhuma equipe encontrada com os filtros aplicados.' : 'Nenhuma equipe encontrada.' }}
                                    </p>
                                    <Button
                                        as-child
                                        v-if="!nome && !tipo"
                                        class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                                    >
                                        <Link :href="route('equipes.create')">
                                            <Plus class="mr-2 h-4 w-4" />
                                            Criar primeira equipe
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
                </CardContent>
            </Card>
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
