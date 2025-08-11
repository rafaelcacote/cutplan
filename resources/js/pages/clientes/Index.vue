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
import { Edit, Eye, Plus, Trash2, Search, Filter, X, ChevronDown } from 'lucide-vue-next';

interface Endereco {
    id: number;
    linha1: string;
    linha2?: string;
    cidade: string;
    estado: string;
    cep?: string;
    pais?: string;
}

interface Cliente {
    id: number;
    nome: string;
    documento?: string;
    email?: string;
    telefone?: string;
    endereco_id: number;
    observacoes?: string;
    endereco: Endereco;
    created_at: string;
    updated_at: string;
}

interface Props {
    clientes: {
        data: Cliente[];
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
        documento?: string;
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
        title: 'Clientes',
        href: '/clientes',
    },
];

// Estados dos filtros
const nome = ref(props.filters?.nome || '');
const documento = ref(props.filters?.documento || '');
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);

// Estados da tabela
const loading = ref(false);
const showDeleteDialog = ref(false);
const clienteToDelete = ref<Cliente|null>(null);

// Opções para dropdowns
const perPageOptions = [
    { label: '10 por página', value: 10 },
    { label: '25 por página', value: 25 },
    { label: '50 por página', value: 50 }
];

const askDeleteCliente = (cliente: Cliente) => {
    clienteToDelete.value = cliente;
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

const confirmDeleteCliente = () => {
    if (clienteToDelete.value) {
        router.delete(`/clientes/${clienteToDelete.value.id}`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                clienteToDelete.value = null;
            }
        });
    }
};

// Funções de filtros e paginação
const updateFilters = () => {
    loading.value = true;
    const params: Record<string, any> = {};
    if (nome.value) params.nome = nome.value;
    if (documento.value) params.documento = documento.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/clientes', params, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            loading.value = false;
        }
    });
};

const clearFilters = () => {
    nome.value = '';
    documento.value = '';
    perPage.value = 10;
    updateFilters();
};

const goToPage = (page: number) => {
    loading.value = true;
    const params: Record<string, any> = { page };
    if (nome.value) params.nome = nome.value;
    if (documento.value) params.documento = documento.value;
    if (perPage.value !== 10) params.per_page = perPage.value;
    router.get('/clientes', params, {
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

let docTimeout: ReturnType<typeof setTimeout>;
watch(documento, () => {
    clearTimeout(docTimeout);
    docTimeout = setTimeout(() => {
        updateFilters();
    }, 500);
});

watch(perPage, () => {
    updateFilters();
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const formatPhoneNumber = (phone?: string) => {
    if (!phone) return '-';
    return phone.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
};

const formatDocument = (doc?: string) => {
    if (!doc) return '-';
    if (doc.length === 11) {
        return doc.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else if (doc.length === 14) {
        return doc.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }
    return doc;
};
</script>

<template>
  <Head title="Clientes" />
  <Toast position="top-right" group="main" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Clientes</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie seus clientes</p>
        </div>
        <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
          <Link :href="route('clientes.create')">
            <Plus class="mr-2 h-4 w-4" />
            Novo Cliente
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
                <Label for="documento">CPF ou CNPJ</Label>
                <InputText
                  id="documento"
                  v-model="documento"
                  placeholder="Buscar por CPF ou CNPJ"
                  class="w-full"
                />
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <Button 
                variant="outline" 
                size="sm" 
                @click="clearFilters"
                v-if="nome || documento || perPage !== 10"
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
          <CardTitle class="text-[#1E293B] dark:text-white">Lista de Clientes</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Mostrando {{ clientes.data.length }} de {{ clientes.total }} clientes
            <span v-if="nome || documento" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
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
              :value="clientes.data"
              class="w-full p-datatable-sm striped highlight-on-hover premium-table"
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
              <Column field="documento" header="CPF/CNPJ" sortable>
                <template #body="{ data }">
                  <span class="font-mono text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ formatDocument(data.documento) }}</span>
                </template>
              </Column>
              <Column field="email" header="Email" sortable>
                <template #body="{ data }">
                  <div v-if="data.email" class="text-sm">
                    <a :href="`mailto:${data.email}`" class="text-[#3B82F6] hover:underline dark:text-[#60A5FA]">{{ data.email }}</a>
                  </div>
                  <span v-else class="text-[#CBD5E1] dark:text-[#64748B]">-</span>
                </template>
              </Column>
              <Column field="telefone" header="Telefone">
                <template #body="{ data }">
                  <div v-if="data.telefone" class="font-mono text-sm">
                    <a :href="`tel:${data.telefone}`" class="text-[#6366F1] hover:underline dark:text-[#A5B4FC]">{{ formatPhoneNumber(data.telefone) }}</a>
                  </div>
                  <span v-else class="text-[#CBD5E1] dark:text-[#64748B]">-</span>
                </template>
              </Column>
              <Column field="endereco.cidade" header="Localização">
                <template #body="{ data }">
                  <div v-if="data.endereco" class="text-sm">
                    <div class="font-medium text-[#1E293B] dark:text-white">{{ data.endereco.cidade }}</div>
                    <div class="text-[#64748B] dark:text-[#CBD5E1]">{{ data.endereco.estado }}</div>
                  </div>
                  <span v-else class="text-[#CBD5E1] dark:text-[#64748B]">-</span>
                </template>
              </Column>
              <Column header="Ações" style="width: 180px">
                <template #body="{ data }">
                  <div class="flex gap-2">
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] dark:text-[#60A5FA]">
                      <Link :href="route('clientes.show', data.id)">
                        <Eye class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] dark:text-[#A5B4FC]">
                      <Link :href="route('clientes.edit', data.id)">
                        <Edit class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button 
                      variant="ghost" 
                      size="sm" 
                      class="text-red-500 hover:text-white hover:bg-red-500/80 hover:scale-105 transition-all duration-300" 
                      @click="askDeleteCliente(data)"
                    >
                      <Trash2 class="h-4 w-4" />
                    </Button>
                  </div>
                </template>
              </Column>
              <template #empty>
                <div class="py-8 text-center">
                  <p class="text-[#64748B] dark:text-[#CBD5E1] mb-4">
                    {{ nome || documento
                      ? 'Nenhum cliente encontrado com os filtros aplicados.'
                      : 'Nenhum cliente encontrado.'
                    }}
                  </p>
                  <Button as-child v-if="!nome && !documento" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                    <Link :href="route('clientes.create')">
                      <Plus class="mr-2 h-4 w-4" />
                      Criar primeiro cliente
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
          <div v-if="clientes.last_page > 1" class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
              Mostrando {{ ((clientes.current_page - 1) * clientes.per_page) + 1 }} a 
              {{ Math.min(clientes.current_page * clientes.per_page, clientes.total) }} 
              de {{ clientes.total }} resultados
            </div>
            <div class="flex items-center gap-2">
              <!-- Botão Anterior -->
              <Button
                variant="outline"
                size="sm"
                @click="goToPage(clientes.current_page - 1)"
                :disabled="clientes.current_page === 1 || loading"
                class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
              >
                Anterior
              </Button>
              <!-- Números das páginas -->
              <div class="flex items-center gap-1">
                <!-- Primeira página -->
                <Button
                  v-if="clientes.current_page > 3"
                  variant="ghost"
                  size="sm"
                  @click="goToPage(1)"
                  :disabled="loading"
                  class="hover:scale-105 transition-transform duration-300"
                >
                  1
                </Button>
                <!-- Reticências início -->
                <span v-if="clientes.current_page > 4" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <!-- Páginas ao redor da atual -->
                <template v-for="page in Array.from({ length: Math.min(5, clientes.last_page) }, (_, i) => {
                  const start = Math.max(1, Math.min(clientes.current_page - 2, clientes.last_page - 4));
                  return start + i;
                }).filter(p => p <= clientes.last_page)" :key="page">
                  <Button
                    :variant="page === clientes.current_page ? 'default' : 'ghost'"
                    size="sm"
                    @click="goToPage(page)"
                    :disabled="loading"
                    class="hover:scale-105 transition-transform duration-300"
                  >
                    {{ page }}
                  </Button>
                </template>
                <!-- Reticências fim -->
                <span v-if="clientes.current_page < clientes.last_page - 3" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <!-- Última página -->
                <Button
                  v-if="clientes.current_page < clientes.last_page - 2"
                  variant="ghost"
                  size="sm"
                  @click="goToPage(clientes.last_page)"
                  :disabled="loading"
                  class="hover:scale-105 transition-transform duration-300"
                >
                  {{ clientes.last_page }}
                </Button>
              </div>
              <!-- Botão Próximo -->
              <Button
                variant="outline"
                size="sm"
                @click="goToPage(clientes.current_page + 1)"
                :disabled="clientes.current_page === clientes.last_page || loading"
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
                <h3 class="text-xl font-bold text-red-500">Excluir Cliente</h3>
                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação <span class='font-semibold text-red-500'>não pode ser desfeita</span>.</p>
              </div>
            </div>
            <!-- Conteúdo -->
            <div class="space-y-4">
              <p class="text-lg text-[#1E293B] dark:text-white">
                Tem certeza que deseja excluir o cliente
                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ clienteToDelete?.nome }}</span>?
              </p>
              <div class="bg-red-50 dark:bg-red-900 rounded-md p-4 border-l-4 border-red-500 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                <span class="text-sm text-red-500 font-medium">Todos os dados relacionados a este cliente serão <u>permanentemente removidos</u>.</span>
              </div>
            </div>
            <!-- Ações -->
            <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
              <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base hover:bg-[#F1F5F9] dark:hover:bg-[#334155] transition-all rounded-lg">
                Cancelar
              </Button>
              <Button variant="destructive" @click="confirmDeleteCliente" class="px-7 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
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
