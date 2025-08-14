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
import AutoComplete from 'primevue/autocomplete';
import InputText from 'primevue/inputtext';
import { Edit, Eye, Plus, Trash2, Search, Filter, X, ChevronDown } from 'lucide-vue-next';

interface Cliente { id: number; nome: string; }
interface Fornecedor { id: number; nome: string; }
interface Contato {
  id: number;
  nome: string;
  email?: string;
  telefone?: string;
  cargo?: string;
  cliente?: Cliente|null;
  fornecedor?: Fornecedor|null;
  created_at: string;
  updated_at: string;
}

interface Props {
  contatos: {
    data: Contato[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{ url: string | null; label: string; active: boolean; }>;
  };
  filters: {
    nome?: string;
    email?: string;
    telefone?: string;
    cargo?: string;
    cliente_id?: number;
    fornecedor_id?: number;
    sort?: string;
    order?: string;
    per_page?: number;
  };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Contatos', href: '/contatos' },
];

const nome = ref(props.filters?.nome || '');
const email = ref(props.filters?.email || '');
const telefone = ref(props.filters?.telefone || '');
const cargo = ref(props.filters?.cargo || '');
const cliente = ref<Cliente|null>(props.filters?.cliente_id ? { id: props.filters.cliente_id, nome: '' } : null);
const fornecedor = ref<Fornecedor|null>(props.filters?.fornecedor_id ? { id: props.filters.fornecedor_id, nome: '' } : null);
const perPage = ref(props.filters?.per_page || 10);
const showFilters = ref(false);
const loading = ref(false);
const showDeleteDialog = ref(false);
const contatoToDelete = ref<Contato|null>(null);
const perPageOptions = [
  { label: '10 por página', value: 10 },
  { label: '25 por página', value: 25 },
  { label: '50 por página', value: 50 }
];

const askDeleteContato = (contato: Contato) => {
  contatoToDelete.value = contato;
  showDeleteDialog.value = true;
};

const toast = useToast();
const page = usePage();
const showToastMessages = () => {
  const successMsg = (page.props.flash as any)?.success;
  if (successMsg) {
    toast.add({ severity: 'success', summary: 'Sucesso', detail: successMsg, life: 4000, group: 'main' });
  }
  const errorMsg = (page.props.flash as any)?.error;
  if (errorMsg) {
    toast.add({ severity: 'error', summary: 'Erro', detail: errorMsg, life: 4000, group: 'main' });
  }
};
onMounted(() => { showToastMessages(); });
watch(() => page.props.flash, () => { showToastMessages(); }, { deep: true });
const confirmDeleteContato = () => {
  if (contatoToDelete.value) {
    router.delete(`/contatos/${contatoToDelete.value.id}`, {
      onSuccess: () => {
        showDeleteDialog.value = false;
        contatoToDelete.value = null;
      }
    });
  }
};
const updateFilters = () => {
  loading.value = true;
  const params: Record<string, any> = {};
  if (nome.value) params.nome = nome.value;
  if (email.value) params.email = email.value;
  if (telefone.value) params.telefone = telefone.value;
  if (cargo.value) params.cargo = cargo.value;
  if (cliente.value) params.cliente_id = cliente.value.id;
  if (fornecedor.value) params.fornecedor_id = fornecedor.value.id;
  if (perPage.value !== 10) params.per_page = perPage.value;
  router.get('/contatos', params, {
    preserveState: true,
    replace: true,
    onFinish: () => { loading.value = false; }
  });
};
const clearFilters = () => {
  nome.value = '';
  email.value = '';
  telefone.value = '';
  cargo.value = '';
  cliente.value = null;
  fornecedor.value = null;
  perPage.value = 10;
  updateFilters();
};
const goToPage = (page: number) => {
  loading.value = true;
  const params: Record<string, any> = { page };
  if (nome.value) params.nome = nome.value;
  if (email.value) params.email = email.value;
  if (telefone.value) params.telefone = telefone.value;
  if (cargo.value) params.cargo = cargo.value;
  if (cliente.value) params.cliente_id = cliente.value.id;
  if (fornecedor.value) params.fornecedor_id = fornecedor.value.id;
  if (perPage.value !== 10) params.per_page = perPage.value;
  router.get('/contatos', params, {
    preserveState: true,
    replace: true,
    onFinish: () => { loading.value = false; }
  });
};
let nomeTimeout: ReturnType<typeof setTimeout>;
watch(nome, () => { clearTimeout(nomeTimeout); nomeTimeout = setTimeout(() => { updateFilters(); }, 500); });
let emailTimeout: ReturnType<typeof setTimeout>;
watch(email, () => { clearTimeout(emailTimeout); emailTimeout = setTimeout(() => { updateFilters(); }, 500); });
let telTimeout: ReturnType<typeof setTimeout>;
watch(telefone, () => { clearTimeout(telTimeout); telTimeout = setTimeout(() => { updateFilters(); }, 500); });
let cargoTimeout: ReturnType<typeof setTimeout>;
watch(cargo, () => { clearTimeout(cargoTimeout); cargoTimeout = setTimeout(() => { updateFilters(); }, 500); });
watch(perPage, () => { updateFilters(); });
// Autocomplete
const clienteSuggestions = ref<Cliente[]>([]);
const fornecedorSuggestions = ref<Fornecedor[]>([]);
const searchClientes = (event: any) => {
  fetch(`/autocomplete/clientes?q=${event.query}`)
    .then(res => res.json())
    .then(data => { clienteSuggestions.value = data; });
};
const searchFornecedores = (event: any) => {
  fetch(`/autocomplete/fornecedores?q=${event.query}`)
    .then(res => res.json())
    .then(data => { fornecedorSuggestions.value = data; });
};
</script>
<template>
  <Head title="Contatos" />
  <Toast position="top-right" group="main" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Contatos</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie seus contatos</p>
        </div>
        <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
          <Link :href="route('contatos.create')">
            <Plus class="mr-2 h-4 w-4" />
            Novo Contato
          </Link>
        </Button>
      </div>
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <div class="flex items-center justify-between">
            <CardTitle class="flex items-center gap-2 text-[#3B82F6] dark:text-[#60A5FA]">
              <Filter class="h-5 w-5" />
              Filtros
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
                <InputText id="nome" v-model="nome" placeholder="Buscar por nome" class="w-full" />
              </div>
              <div class="space-y-2">
                <Label for="email">Email</Label>
                <InputText id="email" v-model="email" placeholder="Buscar por email" class="w-full" />
              </div>
              <div class="space-y-2">
                <Label for="telefone">Telefone</Label>
                <InputText id="telefone" v-model="telefone" placeholder="Buscar por telefone" class="w-full" />
              </div>
              <div class="space-y-2">
                <Label for="cargo">Cargo</Label>
                <InputText id="cargo" v-model="cargo" placeholder="Buscar por cargo" class="w-full" />
              </div>
              <div class="space-y-2">
                <Label for="cliente">Cliente</Label>
                <AutoComplete v-model="cliente" :suggestions="clienteSuggestions" field="nome" @complete="searchClientes" placeholder="Buscar cliente" class="w-full" />
              </div>
              <div class="space-y-2">
                <Label for="fornecedor">Fornecedor</Label>
                <AutoComplete v-model="fornecedor" :suggestions="fornecedorSuggestions" field="nome" @complete="searchFornecedores" placeholder="Buscar fornecedor" class="w-full" />
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <Button variant="outline" size="sm" @click="clearFilters" v-if="nome || email || telefone || cargo || cliente || fornecedor || perPage !== 10" class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                <X class="mr-2 h-4 w-4" />
                Limpar Filtros
              </Button>
            </div>
          </CardContent>
        </transition>
      </Card>
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Lista de Contatos</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Mostrando {{ contatos.data.length }} de {{ contatos.total }} contatos
            <span v-if="nome || email || telefone || cargo || cliente || fornecedor" class="text-[#3B82F6] dark:text-[#60A5FA]">(filtrados)</span>
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="relative">
            <transition name="fade">
              <div v-if="loading" class="absolute inset-0 bg-[#F8FAFC]/80 dark:bg-[#1E293B]/80 flex items-center justify-center z-10 rounded-2xl">
                <div class="w-full">
                  <div class="h-6 bg-[#E0E7EF] dark:bg-[#334155] rounded mb-2 animate-pulse" v-for="i in 8" :key="i"></div>
                </div>
              </div>
            </transition>
            <DataTable :value="contatos.data" class="w-full p-datatable-sm striped highlight-on-hover premium-table" responsiveLayout="scroll" :loading="loading" stripedRows rowHover>
              <Column field="nome" header="Nome" sortable>
                <template #body="{ data }">
                  <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.nome }}</div>
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
                    <a :href="`tel:${data.telefone}`" class="text-[#6366F1] hover:underline dark:text-[#A5B4FC]">{{ data.telefone }}</a>
                  </div>
                  <span v-else class="text-[#CBD5E1] dark:text-[#64748B]">-</span>
                </template>
              </Column>
              <Column field="cargo" header="Cargo">
                <template #body="{ data }">
                  <span>{{ data.cargo || '-' }}</span>
                </template>
              </Column>
              <Column field="cliente.nome" header="Cliente">
                <template #body="{ data }">
                  <span>{{ data.cliente?.nome || '-' }}</span>
                </template>
              </Column>
              <Column field="fornecedor.nome" header="Fornecedor">
                <template #body="{ data }">
                  <span>{{ data.fornecedor?.nome || '-' }}</span>
                </template>
              </Column>
              <Column header="Ações" style="width: 180px">
                <template #body="{ data }">
                  <div class="flex gap-2">
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] dark:text-[#60A5FA]">
                      <Link :href="route('contatos.edit', data.id)">
                        <Edit class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button variant="ghost" size="sm" class="text-red-500 hover:text-white hover:bg-red-500/80 hover:scale-105 transition-all duration-300" @click="askDeleteContato(data)">
                      <Trash2 class="h-4 w-4" />
                    </Button>
                  </div>
                </template>
              </Column>
              <template #empty>
                <div class="py-8 text-center">
                  <p class="text-[#64748B] dark:text-[#CBD5E1] mb-4">
                    {{ nome || email || telefone || cargo || cliente || fornecedor
                      ? 'Nenhum contato encontrado com os filtros aplicados.'
                      : 'Nenhum contato encontrado.'
                    }}
                  </p>
                  <Button as-child v-if="!nome && !email && !telefone && !cargo && !cliente && !fornecedor" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                    <Link :href="route('contatos.create')">
                      <Plus class="mr-2 h-4 w-4" />
                      Criar primeiro contato
                    </Link>
                  </Button>
                  <Button v-else variant="outline" @click="clearFilters" class="mr-2 border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                    <X class="mr-2 h-4 w-4" />
                    Limpar Filtros
                  </Button>
                </div>
              </template>
            </DataTable>
          </div>
          <div v-if="contatos.last_page > 1" class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
              Mostrando {{ ((contatos.current_page - 1) * contatos.per_page) + 1 }} a 
              {{ Math.min(contatos.current_page * contatos.per_page, contatos.total) }} 
              de {{ contatos.total }} resultados
            </div>
            <div class="flex items-center gap-2">
              <Button variant="outline" size="sm" @click="goToPage(contatos.current_page - 1)" :disabled="contatos.current_page === 1 || loading" class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                Anterior
              </Button>
              <div class="flex items-center gap-1">
                <Button v-if="contatos.current_page > 3" variant="ghost" size="sm" @click="goToPage(1)" :disabled="loading" class="hover:scale-105 transition-transform duration-300">1</Button>
                <span v-if="contatos.current_page > 4" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <template v-for="page in Array.from({ length: Math.min(5, contatos.last_page) }, (_, i) => {
                  const start = Math.max(1, Math.min(contatos.current_page - 2, contatos.last_page - 4));
                  return start + i;
                }).filter(p => p <= contatos.last_page)" :key="page">
                  <Button :variant="page === contatos.current_page ? 'default' : 'ghost'" size="sm" @click="goToPage(page)" :disabled="loading" class="hover:scale-105 transition-transform duration-300">{{ page }}</Button>
                </template>
                <span v-if="contatos.current_page < contatos.last_page - 3" class="px-2 text-[#CBD5E1] dark:text-[#64748B]">...</span>
                <Button v-if="contatos.current_page < contatos.last_page - 2" variant="ghost" size="sm" @click="goToPage(contatos.last_page)" :disabled="loading" class="hover:scale-105 transition-transform duration-300">{{ contatos.last_page }}</Button>
              </div>
              <Button variant="outline" size="sm" @click="goToPage(contatos.current_page + 1)" :disabled="contatos.current_page === contatos.last_page || loading" class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                Próximo
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
      <Dialog v-model:visible="showDeleteDialog" modal :closable="false" class="w-full max-w-md mx-auto">
        <template #container="{ closeCallback }">
          <div class="bg-white dark:bg-[#1E293B] rounded-2xl shadow-2xl border border-red-200 dark:border-red-800 p-8 space-y-8 animate-fade-in transition-all duration-300">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 w-14 h-14 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center animate-pulse">
                <Trash2 class="w-7 h-7 text-red-500" />
              </div>
              <div>
                <h3 class="text-xl font-bold text-red-500">Excluir Contato</h3>
                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação <span class='font-semibold text-red-500'>não pode ser desfeita</span>.</p>
              </div>
            </div>
            <div class="space-y-4">
              <p class="text-lg text-[#1E293B] dark:text-white">
                Tem certeza que deseja excluir o contato
                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ contatoToDelete?.nome }}</span>?
              </p>
              <div class="bg-red-50 dark:bg-red-900 rounded-md p-4 border-l-4 border-red-500 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                <span class="text-sm text-red-500 font-medium">Todos os dados relacionados a este contato serão <u>permanentemente removidos</u>.</span>
              </div>
            </div>
            <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
              <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base hover:bg-[#F1F5F9] dark:hover:bg-[#334155] transition-all rounded-lg">
                Cancelar
              </Button>
              <Button variant="destructive" @click="confirmDeleteContato" class="px-7 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
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
.premium-table .p-datatable-tbody > tr { transition: background 0.3s; }
.premium-table .p-datatable-tbody > tr.p-row-odd { background: #F1F5F9; }
.premium-table .p-datatable-tbody > tr.p-row-even { background: #fff; }
.premium-table .p-datatable-tbody > tr:hover { background: #E0E7EF !important; cursor: pointer; }
.dark .premium-table .p-datatable-tbody > tr.p-row-odd { background: #1E293B; }
.dark .premium-table .p-datatable-tbody > tr.p-row-even { background: #334155; }
.dark .premium-table .p-datatable-tbody > tr:hover { background: #334155 !important; }
.p-button, .p-dropdown, .p-inputtext { border-radius: 0.75rem !important; transition: all 0.3s; }
.p-button:hover { transform: scale(1.05); box-shadow: 0 4px 24px 0 rgba(59,130,246,0.10); }
.p-dropdown-panel .p-dropdown-items .p-dropdown-item.p-highlight { background: linear-gradient(90deg, #3B82F6 0%, #6366F1 100%) !important; color: #fff !important; }
.p-toast { z-index: 9999; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.fade-in { animation: fadeIn 0.3s; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
