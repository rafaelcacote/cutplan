<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch, computed } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { Edit, Eye, Plus, Trash2, Search, Filter, X, ChevronDown, Package } from 'lucide-vue-next';

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
    created_at: string;
    updated_at: string;
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
    tipos: TipoMaterial[];
    filters: {
        nome?: string;
        sku?: string;
        tipo_id?: number;
        ativo?: boolean;
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
        title: 'Materiais',
        href: '/materiais',
    },
];

// Estados dos filtros
const nome = ref(props.filters?.nome || '');
const sku = ref(props.filters?.sku || '');
const tipoId = ref(props.filters?.tipo_id || null);
const ativo = ref(props.filters?.ativo);
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);

// Estados da tabela
const loading = ref(false);
const showDeleteDialog = ref(false);
const materialToDelete = ref<Material|null>(null);

// Opções para dropdowns
const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 }
];

const statusOptions = [
    { label: 'Todos', value: null },
    { label: 'Ativo', value: true },
    { label: 'Inativo', value: false }
];

const tipoOptions = computed(() => {
    return [
        { label: 'Todos os tipos', value: null },
        ...props.tipos.map(tipo => ({ label: tipo.nome, value: tipo.id }))
    ];
});

const askDeleteMaterial = (material: Material) => {
    materialToDelete.value = material;
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
        boxShadow: '0 4px 24px 0 rgba(34,197,94,0.15)'
      },
      icon: 'pi pi-check',
      contentClass: 'flex items-center gap-2',
      class: 'fade-in'
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
        boxShadow: '0 4px 24px 0 rgba(239,68,68,0.15)'
      },
      icon: 'pi pi-exclamation-triangle',
      contentClass: 'flex items-center gap-2',
      class: 'fade-in'
    });
  }
};

onMounted(() => {
    showToastMessages();
});

// Observa mudanças nas flash messages
watch(() => page.props.flash, () => {
    showToastMessages();
}, { deep: true });

const confirmDeleteMaterial = () => {
    if (materialToDelete.value) {
        router.delete(`/materiais/${materialToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                materialToDelete.value = null;
            }
        });
    }
};

// Funções de filtros e paginação
const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (nome.value) params.nome = nome.value;
    if (sku.value) params.sku = sku.value;
    if (tipoId.value) params.tipo_id = tipoId.value;
    if (ativo.value !== undefined && ativo.value !== null) params.ativo = ativo.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/materiais', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        }
    });
};

const clearFilters = () => {
    nome.value = '';
    sku.value = '';
    tipoId.value = null;
    ativo.value = undefined;
    perPage.value = 10;
    updateFilters();
};

const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (nome.value) params.nome = nome.value;
    if (sku.value) params.sku = sku.value;
    if (tipoId.value) params.tipo_id = tipoId.value;
    if (ativo.value !== undefined && ativo.value !== null) params.ativo = ativo.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/materiais', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        }
    });
};

// Debounce para busca
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

watch([tipoId, ativo, perPage], () => {
    updateFilters();
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR');
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
  <Head title="Materiais" />
  <Toast position="top-right" group="main" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA] flex items-center gap-2">
            <Package class="h-8 w-8" />
            Materiais
          </h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie seu estoque de materiais</p>
        </div>
        <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
          <Link :href="route('materiais.create')">
            <Plus class="mr-2 h-4 w-4" />
            Novo Material
          </Link>
        </Button>
      </div>

      <!-- Filtros -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
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
              class="text-[#64748B] dark:text-[#CBD5E1] hover:bg-[#F1F5F9] dark:hover:bg-[#334155] rounded-full transition-all duration-300"
            >
              <ChevronDown 
                class="h-4 w-4 transition-transform duration-200" 
                :class="{ 'rotate-180': showFilters }"
              />
            </Button>
          </div>
        </CardHeader>
        <transition name="fade">
          <CardContent v-if="showFilters" class="pt-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="space-y-2">
                <Label for="nome">Nome</Label>
                <InputText
                  id="nome"
                  v-model="nome"
                  placeholder="Buscar por nome"
                  class="w-full"
                />
              </div>
              <div class="space-y-2">
                <Label for="sku">SKU</Label>
                <InputText
                  id="sku"
                  v-model="sku"
                  placeholder="Buscar por SKU"
                  class="w-full"
                />
              </div>
              <div class="space-y-2">
                <Label for="tipo">Tipo</Label>
                <Dropdown
                  v-model="tipoId"
                  :options="tipoOptions"
                  optionLabel="label"
                  optionValue="value"
                  placeholder="Selecione o tipo"
                  class="w-full"
                />
              </div>
              <div class="space-y-2">
                <Label for="status">Status</Label>
                <Dropdown
                  v-model="ativo"
                  :options="statusOptions"
                  optionLabel="label"
                  optionValue="value"
                  placeholder="Selecione o status"
                  class="w-full"
                />
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <Button 
                variant="outline" 
                size="sm" 
                @click="clearFilters"
                v-if="nome || sku || tipoId || ativo !== undefined || perPage !== 10"
                class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
              >
                <X class="mr-2 h-4 w-4" />
                Limpar Filtros
              </Button>
            </div>
          </CardContent>
        </transition>
      </Card>

      <!-- Tabela -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Lista de Materiais</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Mostrando {{ materiais.data.length }} de {{ materiais.total }} materiais
            <span v-if="nome || sku || tipoId || ativo !== undefined" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="relative">
            <!-- Loading skeleton -->
            <transition name="fade">
              <div v-if="loading" class="absolute inset-0 bg-[#F8FAFC]/80 dark:bg-[#1E293B]/80 flex items-center justify-center z-10 rounded-2xl">
                <div class="w-full">
                  <div class="h-6 bg-[#E0E7EF] dark:bg-[#334155] rounded mb-2 animate-pulse" v-for="i in 8" :key="i"></div>
                </div>
              </div>
            </transition>

            <DataTable
              :value="materiais.data"
              class="w-full p-datatable-sm striped highlight-on-hover premium-table"
              responsiveLayout="scroll"
              :loading="loading"
              stripedRows
              rowHover
            >
              <Column field="sku" header="SKU" sortable>
                <template #body="{ data }">
                  <div class="font-mono text-sm font-semibold text-[#1E293B] dark:text-white">{{ data.sku }}</div>
                </template>
              </Column>
              <Column field="nome" header="Nome" sortable>
                <template #body="{ data }">
                  <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.nome }}</div>
                </template>
              </Column>
              <Column field="tipo.nome" header="Tipo">
                <template #body="{ data }">
                  <span class="text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ data.tipo?.nome || '-' }}</span>
                </template>
              </Column>
              <Column field="unidade.nome" header="Unidade">
                <template #body="{ data }">
                  <span class="text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ data.unidade?.nome || '-' }}</span>
                </template>
              </Column>
              <Column field="preco_custo" header="Preço Custo" sortable>
                <template #body="{ data }">
                  <span class="font-mono text-sm">{{ formatCurrency(data.preco_custo) }}</span>
                </template>
              </Column>
              <Column field="estoque_minimo" header="Estoque Mín." sortable>
                <template #body="{ data }">
                  <span class="font-mono text-sm">{{ formatNumber(data.estoque_minimo) }}</span>
                </template>
              </Column>
              <Column field="ativo" header="Status">
                <template #body="{ data }">
                  <span 
                    class="px-2 py-1 rounded-full text-xs font-medium"
                    :class="data.ativo 
                      ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                      : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'"
                  >
                    {{ data.ativo ? 'Ativo' : 'Inativo' }}
                  </span>
                </template>
              </Column>
              <Column header="Ações" style="width: 180px">
                <template #body="{ data }">
                  <div class="flex gap-2">
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] dark:text-[#60A5FA]">
                      <Link :href="route('materiais.show', data.id)">
                        <Eye class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] dark:text-[#A5B4FC]">
                      <Link :href="route('materiais.edit', data.id)">
                        <Edit class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button 
                      variant="ghost" 
                      size="sm" 
                      class="text-red-500 hover:text-white hover:bg-red-500/80 hover:scale-105 transition-all duration-300" 
                      @click="askDeleteMaterial(data)"
                    >
                      <Trash2 class="h-4 w-4" />
                    </Button>
                  </div>
                </template>
              </Column>
              <template #empty>
                <div class="py-8 text-center">
                  <p class="text-[#64748B] dark:text-[#CBD5E1] mb-4">
                    {{ nome || sku || tipoId || ativo !== undefined
                      ? 'Nenhum material encontrado com os filtros aplicados.'
                      : 'Nenhum material encontrado.'
                    }}
                  </p>
                  <Button as-child v-if="!nome && !sku && !tipoId && ativo === undefined" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                    <Link :href="route('materiais.create')">
                      <Plus class="mr-2 h-4 w-4" />
                      Criar primeiro material
                    </Link>
                  </Button>
                  <Button 
                    v-else 
                    variant="outline" 
                    @click="clearFilters"
                    class="mr-2 border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
                  >
                    <X class="mr-2 h-4 w-4" />
                    Limpar Filtros
                  </Button>
                </div>
              </template>
            </DataTable>
          </div>

          <!-- Paginação customizada -->
          <div v-if="materiais.last_page > 1" class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
              Mostrando {{ ((materiais.current_page - 1) * materiais.per_page) + 1 }} a 
              {{ Math.min(materiais.current_page * materiais.per_page, materiais.total) }} 
              de {{ materiais.total }} resultados
            </div>
            <div class="flex items-center gap-2">
              <!-- Botão Anterior -->
              <Button
                variant="outline"
                size="sm"
                @click="goToPage(materiais.current_page - 1)"
                :disabled="materiais.current_page === 1 || loading"
                class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
              >
                Anterior
              </Button>
              <!-- Números das páginas -->
              <div class="flex items-center gap-1">
                <!-- Primeira página -->
                <Button
                  v-if="materiais.current_page > 3"
                  variant="ghost"
                  size="sm"
                  @click="goToPage(1)"
                  :disabled="loading"
                  class="hover:scale-105 transition-transform duration-300"
                >
                  1
                </Button>
                <!-- Reticências início -->
                <span v-if="materiais.current_page > 4" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <!-- Páginas ao redor da atual -->
                <template v-for="page in Array.from({ length: Math.min(5, materiais.last_page) }, (_, i) => {
                  const start = Math.max(1, Math.min(materiais.current_page - 2, materiais.last_page - 4));
                  return start + i;
                }).filter(p => p <= materiais.last_page)" :key="page">
                  <Button
                    :variant="page === materiais.current_page ? 'default' : 'ghost'"
                    size="sm"
                    @click="goToPage(page)"
                    :disabled="loading"
                    class="hover:scale-105 transition-transform duration-300"
                  >
                    {{ page }}
                  </Button>
                </template>
                <!-- Reticências fim -->
                <span v-if="materiais.current_page < materiais.last_page - 3" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <!-- Última página -->
                <Button
                  v-if="materiais.current_page < materiais.last_page - 2"
                  variant="ghost"
                  size="sm"
                  @click="goToPage(materiais.last_page)"
                  :disabled="loading"
                  class="hover:scale-105 transition-transform duration-300"
                >
                  {{ materiais.last_page }}
                </Button>
              </div>
              <!-- Botão Próximo -->
              <Button
                variant="outline"
                size="sm"
                @click="goToPage(materiais.current_page + 1)"
                :disabled="materiais.current_page === materiais.last_page || loading"
                class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
              >
                Próximo
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Modal de confirmação de exclusão -->
      <Dialog v-model:visible="showDeleteDialog" modal :closable="false" class="w-full max-w-md mx-auto">
        <template #container="{ closeCallback }">
          <div class="bg-white dark:bg-[#1E293B] rounded-2xl shadow-2xl border border-red-200 dark:border-red-800 p-8 space-y-8 animate-fade-in transition-all duration-300">
            <!-- Header com ícone animado -->
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 w-14 h-14 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center animate-pulse">
                <Trash2 class="w-7 h-7 text-red-500" />
              </div>
              <div>
                <h3 class="text-xl font-bold text-red-500">Excluir Material</h3>
                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação <span class='font-semibold text-red-500'>não pode ser desfeita</span>.</p>
              </div>
            </div>
            <!-- Conteúdo -->
            <div class="space-y-4">
              <p class="text-lg text-[#1E293B] dark:text-white">
                Tem certeza que deseja excluir o material
                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ materialToDelete?.nome }}</span>
                (SKU: <span class="font-mono">{{ materialToDelete?.sku }}</span>)?
              </p>
              <div class="bg-red-50 dark:bg-red-900 rounded-md p-4 border-l-4 border-red-500 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                <span class="text-sm text-red-500 font-medium">Todos os dados relacionados a este material serão <u>permanentemente removidos</u>.</span>
              </div>
            </div>
            <!-- Ações -->
            <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
              <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base hover:bg-[#F1F5F9] dark:hover:bg-[#334155] transition-all rounded-lg">
                Cancelar
              </Button>
              <Button variant="destructive" @click="confirmDeleteMaterial" class="px-7 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
                <Trash2 class="w-5 h-5" />
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
  background: #F1F5F9;
}
.premium-table .p-datatable-tbody > tr.p-row-even {
  background: #fff;
}
.premium-table .p-datatable-tbody > tr:hover {
  background: #E0E7EF !important;
  cursor: pointer;
}
.dark .premium-table .p-datatable-tbody > tr.p-row-odd {
  background: #1E293B;
}
.dark .premium-table .p-datatable-tbody > tr.p-row-even {
  background: #334155;
}
.dark .premium-table .p-datatable-tbody > tr:hover {
  background: #334155 !important;
}
.p-button, .p-dropdown, .p-inputtext {
  border-radius: 0.75rem !important;
  transition: all 0.3s;
}
.p-button:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 24px 0 rgba(59,130,246,0.10);
}
.p-dropdown-panel .p-dropdown-items .p-dropdown-item.p-highlight {
  background: linear-gradient(90deg, #3B82F6 0%, #6366F1 100%) !important;
  color: #fff !important;
}
.p-toast {
  z-index: 9999;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.fade-in {
  animation: fadeIn 0.3s;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>
