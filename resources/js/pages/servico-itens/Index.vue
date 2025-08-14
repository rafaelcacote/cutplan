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
import Tag from 'primevue/tag';
import { Edit, Eye, Plus, Trash2, Search, Filter, X, ChevronDown, Layers } from 'lucide-vue-next';

interface Servico {
    id: number;
    nome: string;
}

interface ServicoItem {
    id: number;
    servico_id: number;
    descricao_item: string;
    ordem?: number;
    opcional: boolean;
    servico: Servico;
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
        servico_nome?: string;
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
const servicoNome = ref(props.filters?.servico_nome || '');
const opcional = ref(props.filters?.opcional || '');
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);

// Estados da tabela
const loading = ref(false);
const showDeleteDialog = ref(false);
const servicoItemToDelete = ref<ServicoItem|null>(null);

// Opções para dropdowns
const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 }
];

const opcionalOptions = [
    { label: 'Todos', value: '' },
    { label: 'Obrigatório', value: '0' },
    { label: 'Opcional', value: '1' }
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

const confirmDeleteServicoItem = () => {
    if (servicoItemToDelete.value) {
        router.delete(`/servico-itens/${servicoItemToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                servicoItemToDelete.value = null;
            }
        });
    }
};

// Funções de filtros e paginação
const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (descricaoItem.value) params.descricao_item = descricaoItem.value;
    if (servicoNome.value) params.servico_nome = servicoNome.value;
    if (opcional.value !== '') params.opcional = opcional.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/servico-itens', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        }
    });
};

const clearFilters = () => {
    descricaoItem.value = '';
    servicoNome.value = '';
    opcional.value = '';
    perPage.value = 10;
    updateFilters();
};

const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (descricaoItem.value) params.descricao_item = descricaoItem.value;
    if (servicoNome.value) params.servico_nome = servicoNome.value;
    if (opcional.value !== '') params.opcional = opcional.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/servico-itens', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        }
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

let servicoTimeout: ReturnType<typeof setTimeout>;
watch(servicoNome, () => {
    clearTimeout(servicoTimeout);
    servicoTimeout = setTimeout(() => {
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
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Itens de Serviço</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie os itens dos seus serviços</p>
        </div>
        <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
          <Link :href="route('servico-itens.create')">
            <Plus class="mr-2 h-4 w-4" />
            Novo Item
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
              <div class="space-y-2">
                <Label for="servico_nome">Serviço</Label>
                <InputText
                  id="servico_nome"
                  v-model="servicoNome"
                  placeholder="Buscar por serviço"
                  class="w-full"
                  :class="{ 'border-[#3B82F6] ring-2 ring-[#3B82F6]/20': servicoNome }"
                />
              </div>
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
            <div class="flex justify-end mt-4">
              <Button 
                variant="outline" 
                size="sm" 
                @click="clearFilters"
                v-if="descricaoItem || servicoNome || opcional !== '' || perPage !== 10"
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
          <CardTitle class="text-[#1E293B] dark:text-white">Lista de Itens de Serviço</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Mostrando {{ servicoItens.data.length }} de {{ servicoItens.total }} itens
            <span v-if="descricaoItem || servicoNome || opcional !== ''" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="relative">
            <!-- Loading overlay -->
            <transition name="fade">
              <div v-if="loading" class="absolute inset-0 bg-white/95 dark:bg-[#1E293B]/95 backdrop-blur-sm flex items-center justify-center z-10 rounded-2xl">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-8 h-8 border-3 border-[#3B82F6] border-t-transparent rounded-full animate-spin"></div>
                  <p class="text-sm text-[#64748B] dark:text-[#CBD5E1] font-medium">Carregando itens...</p>
                </div>
              </div>
            </transition>

            <DataTable
              :value="servicoItens.data"
              class="w-full p-datatable-sm striped highlight-on-hover premium-table"
              responsiveLayout="scroll"
              :loading="loading"
              stripedRows
              rowHover
            >
              <Column field="ordem" header="Ordem" sortable style="width: 80px">
                <template #body="{ data }">
                  <div class="text-center">
                    <span v-if="data.ordem" class="inline-flex items-center justify-center w-8 h-8 bg-[#3B82F6] text-white rounded-full text-sm font-bold">
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
              <Column field="servico.nome" header="Serviço" sortable>
                <template #body="{ data }">
                  <div class="text-sm">
                    <div class="font-medium text-[#3B82F6] dark:text-[#60A5FA]">{{ data.servico.nome }}</div>
                  </div>
                </template>
              </Column>
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
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] dark:text-[#60A5FA]">
                      <Link :href="route('servico-itens.show', data.id)">
                        <Eye class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] dark:text-[#A5B4FC]">
                      <Link :href="route('servico-itens.edit', data.id)">
                        <Edit class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button 
                      variant="ghost" 
                      size="sm" 
                      class="text-red-500 hover:text-white hover:bg-red-500/80 hover:scale-105 transition-all duration-300" 
                      @click="askDeleteServicoItem(data)"
                    >
                      <Trash2 class="h-4 w-4" />
                    </Button>
                  </div>
                </template>
              </Column>
              <template #empty>
                <div class="py-8 text-center">
                  <p class="text-[#64748B] dark:text-[#CBD5E1] mb-4">
                    {{ descricaoItem || servicoNome || opcional !== ''
                      ? 'Nenhum item encontrado com os filtros aplicados.'
                      : 'Nenhum item de serviço encontrado.'
                    }}
                  </p>
                  <Button as-child v-if="!descricaoItem && !servicoNome && opcional === ''" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                    <Link :href="route('servico-itens.create')">
                      <Plus class="mr-2 h-4 w-4" />
                      Criar primeiro item
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
          <div v-if="servicoItens.last_page > 1" class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
              Mostrando {{ ((servicoItens.current_page - 1) * servicoItens.per_page) + 1 }} a 
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
                class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
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
                  class="hover:scale-105 transition-transform duration-300"
                >
                  1
                </Button>
                <!-- Reticências início -->
                <span v-if="servicoItens.current_page > 4" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <!-- Páginas ao redor da atual -->
                <template v-for="page in Array.from({ length: Math.min(5, servicoItens.last_page) }, (_, i) => {
                  const start = Math.max(1, Math.min(servicoItens.current_page - 2, servicoItens.last_page - 4));
                  return start + i;
                }).filter(p => p <= servicoItens.last_page)" :key="page">
                  <Button
                    :variant="page === servicoItens.current_page ? 'default' : 'ghost'"
                    size="sm"
                    @click="goToPage(page)"
                    :disabled="loading"
                    class="hover:scale-105 transition-transform duration-300"
                  >
                    {{ page }}
                  </Button>
                </template>
                <!-- Reticências fim -->
                <span v-if="servicoItens.current_page < servicoItens.last_page - 3" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <!-- Última página -->
                <Button
                  v-if="servicoItens.current_page < servicoItens.last_page - 2"
                  variant="ghost"
                  size="sm"
                  @click="goToPage(servicoItens.last_page)"
                  :disabled="loading"
                  class="hover:scale-105 transition-transform duration-300"
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
                <h3 class="text-xl font-bold text-red-500">Excluir Item</h3>
                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação <span class='font-semibold text-red-500'>não pode ser desfeita</span>.</p>
              </div>
            </div>
            <!-- Conteúdo -->
            <div class="space-y-4">
              <p class="text-lg text-[#1E293B] dark:text-white">
                Tem certeza que deseja excluir o item
                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ servicoItemToDelete?.descricao_item }}</span>?
              </p>
              <div class="bg-red-50 dark:bg-red-900 rounded-md p-4 border-l-4 border-red-500 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                <span class="text-sm text-red-500 font-medium">Todos os dados relacionados a este item serão <u>permanentemente removidos</u>.</span>
              </div>
            </div>
            <!-- Ações -->
            <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
              <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base hover:bg-[#F1F5F9] dark:hover:bg-[#334155] transition-all rounded-lg">
                Cancelar
              </Button>
              <Button variant="destructive" @click="confirmDeleteServicoItem" class="px-7 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
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
