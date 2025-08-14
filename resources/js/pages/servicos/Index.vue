<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import { Edit, Eye, Plus, Trash2, Filter, X, ChevronDown, Briefcase } from 'lucide-vue-next';

interface Servico {
    id: number;
    nome: string;
    descricao?: string;
    preco_base?: string;
    categoria?: string;
    ativo: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    servicos: {
        data: Servico[];
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
        categoria?: string;
        ativo?: string;
        sort?: string;
        order?: string;
        per_page?: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Serviços', href: '/servicos' },
];

const nome = ref(props.filters?.nome || '');
const categoria = ref(props.filters?.categoria || '');
const ativo = ref(props.filters?.ativo ?? '');
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);
const loading = ref(false);
const showDeleteDialog = ref(false);
const servicoToDelete = ref<Servico|null>(null);
const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 }
];
const ativoOptions = [
    { label: 'Todos', value: '' },
    { label: 'Ativo', value: 1 },
    { label: 'Inativo', value: 0 }
];
const askDeleteServico = (servico: Servico) => {
    servicoToDelete.value = servico;
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
      style: { background: '#22C55E', color: '#fff', borderRadius: '0.75rem', boxShadow: '0 4px 24px 0 rgba(34,197,94,0.15)' },
      icon: 'pi pi-check',
      contentClass: 'flex items-center gap-2',
      class: 'fade-in'
    });
  }
};
onMounted(() => { showToastMessages(); });
watch(() => page.props.flash, () => { showToastMessages(); }, { deep: true });
const confirmDeleteServico = () => {
    if (servicoToDelete.value) {
        router.delete(`/servicos/${servicoToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                servicoToDelete.value = null;
            }
        });
    }
};
const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (nome.value) params.nome = nome.value;
    if (categoria.value) params.categoria = categoria.value;
    if (ativo.value !== '') params.ativo = ativo.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/servicos', params, {
        preserveState: true,
        replace: true,
        onFinish: () => { loading.value = false; }
    });
};
let nomeTimeout: ReturnType<typeof setTimeout>;
watch(nome, () => {
    clearTimeout(nomeTimeout);
    nomeTimeout = setTimeout(() => { updateFilters(); }, 500);
});
let categoriaTimeout: ReturnType<typeof setTimeout>;
watch(categoria, () => {
    clearTimeout(categoriaTimeout);
    categoriaTimeout = setTimeout(() => { updateFilters(); }, 500);
});
const clearFilters = () => {
    nome.value = '';
    categoria.value = '';
    ativo.value = '';
    perPage.value = 10;
    updateFilters();
};
const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (nome.value) params.nome = nome.value;
    if (categoria.value) params.categoria = categoria.value;
    if (ativo.value !== '') params.ativo = ativo.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/servicos', params, {
        preserveState: true,
        replace: true,
        onFinish: () => { loading.value = false; }
    });
};
</script>
<template>
    <Head title="Serviços" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Serviços</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie seus serviços</p>
                </div>
                <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                    <Link :href="route('servicos.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Serviço
                    </Link>
                </Button>
            </div>
            <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle class="flex items-center gap-2 text-[#3B82F6] dark:text-[#60A5FA]">
                            <Filter class="h-5 w-5" />
                            Filtros
                            <div v-if="loading" class="ml-2 w-4 h-4 border-2 border-[#3B82F6] border-t-transparent rounded-full animate-spin"></div>
                        </CardTitle>
                        <Button variant="ghost" size="sm" @click="showFilters = !showFilters" class="text-[#64748B] dark:text-[#CBD5E1] hover:bg-[#F1F5F9] dark:hover:bg-[#334155] rounded-full transition-all duration-300">
                            <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': showFilters }" />
                        </Button>
                    </div>
                </CardHeader>
                <transition name="fade">
                    <CardContent v-if="showFilters" class="pt-0">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <Label for="nome">Nome</Label>
                                <InputText 
                                    id="nome" 
                                    v-model="nome" 
                                    placeholder="Buscar por nome" 
                                    class="w-full"
                                    :class="{ 'border-[#3B82F6] ring-2 ring-[#3B82F6]/20': nome }" 
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="categoria">Categoria</Label>
                                <InputText 
                                    id="categoria" 
                                    v-model="categoria" 
                                    placeholder="Buscar por categoria" 
                                    class="w-full"
                                    :class="{ 'border-[#3B82F6] ring-2 ring-[#3B82F6]/20': categoria }" 
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="ativo">Status</Label>
                                <Dropdown 
                                    id="ativo" 
                                    v-model="ativo" 
                                    :options="ativoOptions" 
                                    option-label="label" 
                                    option-value="value" 
                                    class="w-full"
                                    :class="{ 'border-[#3B82F6] ring-2 ring-[#3B82F6]/20': ativo !== '' }"
                                />
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <Button variant="outline" size="sm" @click="clearFilters" v-if="nome || categoria || ativo || perPage !== 10" class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                                <X class="mr-2 h-4 w-4" />
                                Limpar Filtros
                            </Button>
                        </div>
                    </CardContent>
                </transition>
            </Card>
            <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Lista de Serviços</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
                        Mostrando {{ props.servicos.data.length }} de {{ props.servicos.total }} serviços
                        <span v-if="nome || categoria || ativo" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="relative">
                        <!-- Loading overlay mais elegante -->
                        <transition name="fade">
                            <div v-if="loading" class="absolute inset-0 bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-sm flex items-center justify-center z-10 rounded-lg">
                                <div class="text-center">
                                    <div class="relative">
                                        <div class="w-16 h-16 border-4 border-gray-200 dark:border-gray-700 rounded-full"></div>
                                        <div class="absolute top-0 left-0 w-16 h-16 border-4 border-[#3B82F6] border-t-transparent rounded-full animate-spin"></div>
                                    </div>
                                    <p class="mt-4 text-[#3B82F6] dark:text-[#60A5FA] font-medium">
                                        {{ nome || categoria || ativo !== '' ? 'Filtrando serviços...' : 'Carregando serviços...' }}
                                    </p>
                                </div>
                            </div>
                        </transition>

                        <!-- Estado vazio quando não há resultados -->
                        <div v-if="!loading && props.servicos.data.length === 0" class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                <Briefcase class="w-12 h-12 text-gray-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ nome || categoria || ativo !== '' 
                                    ? 'Nenhum serviço encontrado com os filtros aplicados.' 
                                    : 'Nenhum serviço encontrado.' 
                                }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                {{ nome || categoria || ativo !== '' 
                                    ? 'Tente ajustar os filtros ou limpar a pesquisa.' 
                                    : 'Comece criando seu primeiro serviço.' 
                                }}
                            </p>
                            <Button v-if="!nome && !categoria && ativo === ''" as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                                <Link :href="route('servicos.create')">
                                    <Plus class="mr-2 h-4 w-4" />
                                    Criar primeiro serviço
                                </Link>
                            </Button>
                            <Button v-else variant="outline" @click="clearFilters" class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                                <X class="mr-2 h-4 w-4" />
                                Limpar Filtros
                            </Button>
                        </div>

                        <!-- Tabela com efeito suave -->
                        <div v-show="!loading" class="transition-opacity duration-300" :class="{ 'opacity-50': loading }">
                            <DataTable v-if="props.servicos.data.length > 0" :value="props.servicos.data" class="w-full p-datatable-sm striped highlight-on-hover premium-table" responsiveLayout="scroll" stripedRows rowHover>
                            <Column field="nome" header="Nome" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.nome }}</div>
                                </template>
                            </Column>
                            <Column field="categoria" header="Categoria" sortable>
                                <template #body="{ data }">
                                    <span class="text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ data.categoria || '-' }}</span>
                                </template>
                            </Column>
                            <Column field="preco_base" header="Preço Base" sortable>
                                <template #body="{ data }">
                                    <span class="text-sm">{{ data.preco_base ? `R$ ${parseFloat(data.preco_base).toFixed(2)}` : '-' }}</span>
                                </template>
                            </Column>
                            <Column field="ativo" header="Status" sortable>
                                <template #body="{ data }">
                                    <span :class="data.ativo ? 'text-green-600' : 'text-red-500'">{{ data.ativo ? 'Ativo' : 'Inativo' }}</span>
                                </template>
                            </Column>
                            <Column header="Ações" style="width: 180px">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] dark:text-[#60A5FA]">
                                            <Link :href="route('servicos.show', data.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] dark:text-[#A5B4FC]">
                                            <Link :href="route('servicos.edit', data.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" class="text-red-500 transition-all duration-300 hover:scale-105 hover:bg-red-500/80 hover:text-white" @click="askDeleteServico(data)">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Dialog v-model:visible="showDeleteDialog" :modal="true" :closable="false" :style="{ width: '100%', maxWidth: '28rem' }">
                <div class="bg-white dark:bg-[#1E293B] rounded-2xl shadow-2xl border border-red-200 dark:border-red-800 p-8 space-y-8 animate-fade-in transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center animate-pulse">
                            <Trash2 class="w-7 h-7 text-red-500" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-500">Excluir Serviço</h3>
                            <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação <span class='font-semibold text-red-500'>não pode ser desfeita</span>.</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-lg text-[#1E293B] dark:text-white">
                            Tem certeza que deseja excluir o serviço
                            <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ servicoToDelete?.nome }}</span>?
                        </p>
                        <div class="bg-red-50 dark:bg-red-900 rounded-md p-4 border-l-4 border-red-500 flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                            <span class="text-sm text-red-500 font-medium">Todos os dados relacionados a este serviço serão <u>permanentemente removidos</u>.</span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
                        <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base hover:bg-[#F1F5F9] dark:hover:bg-[#334155] transition-all rounded-lg">Cancelar</Button>
                        <Button variant="destructive" @click="confirmDeleteServico" class="px-7 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
                            <Trash2 class="w-5 h-5" />
                            <span>Excluir</span>
                        </Button>
                    </div>
                </div>
            </Dialog>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.premium-table {
    transition: all 0.3s ease;
}

.premium-table:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
