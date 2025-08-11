<template>
  <Head title="Fornecedores" />
  <Toast position="top-right" group="main" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Fornecedores</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie seus fornecedores</p>
        </div>
        <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
          <Link :href="route('fornecedores.create')">
            <Plus class="mr-2 h-4 w-4" />
            Novo Fornecedor
          </Link>
        </Button>
      </div>

      <!-- Tabela -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Lista de Fornecedores</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Mostrando {{ fornecedores.data.length }} fornecedores
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="relative">
            <DataTable
              :value="fornecedores.data"
              class="w-full p-datatable-sm striped highlight-on-hover premium-table"
              responsiveLayout="scroll"
              stripedRows
              rowHover
            >
              <Column field="nome" header="Nome" sortable>
                <template #body="{ data }">
                  <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.nome }}</div>
                </template>
              </Column>
              <Column field="documento" header="Documento" sortable>
                <template #body="{ data }">
                  <span class="font-mono text-sm text-[#64748B] dark:text-[#CBD5E1]">{{ data.documento }}</span>
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
              <Column field="observacoes" header="Observações">
                <template #body="{ data }">
                  <span class="text-sm">{{ data.observacoes }}</span>
                </template>
              </Column>
              <Column header="Ações" style="width: 180px">
                <template #body="{ data }">
                  <div class="flex gap-2">
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#3B82F6]/10 to-[#6366F1]/10 text-[#3B82F6] dark:text-[#60A5FA]">
                      <Link :href="route('fornecedores.show', data.id)">
                        <Eye class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button variant="ghost" size="sm" as-child class="hover:scale-105 transition-transform duration-300 bg-gradient-to-r from-[#6366F1]/10 to-[#3B82F6]/10 text-[#6366F1] dark:text-[#A5B4FC]">
                      <Link :href="route('fornecedores.edit', data.id)">
                        <Edit class="h-4 w-4" />
                      </Link>
                    </Button>
                    <Button 
                      variant="ghost" 
                      size="sm" 
                      class="text-red-500 hover:text-white hover:bg-red-500/80 hover:scale-105 transition-all duration-300" 
                      @click="askDeleteFornecedor(data)"
                    >
                      <Trash2 class="h-4 w-4" />
                    </Button>
                  </div>
                </template>
              </Column>
              <template #empty>
                <div class="py-8 text-center">
                  <p class="text-[#64748B] dark:text-[#CBD5E1] mb-4">
                    Nenhum fornecedor encontrado.
                  </p>
                  <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                    <Link :href="route('fornecedores.create')">
                      <Plus class="mr-2 h-4 w-4" />
                      Criar primeiro fornecedor
                    </Link>
                  </Button>
                </div>
              </template>
            </DataTable>
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
                <h3 class="text-xl font-bold text-red-500">Excluir Fornecedor</h3>
                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação <span class='font-semibold text-red-500'>não pode ser desfeita</span>.</p>
              </div>
            </div>
            <!-- Conteúdo -->
            <div class="space-y-4">
              <p class="text-lg text-[#1E293B] dark:text-white">
                Tem certeza que deseja excluir o fornecedor
                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ fornecedorToDelete?.nome }}</span>?
              </p>
              <div class="bg-red-50 dark:bg-red-900 rounded-md p-4 border-l-4 border-red-500 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                <span class="text-sm text-red-500 font-medium">Todos os dados relacionados a este fornecedor serão <u>permanentemente removidos</u>.</span>
              </div>
            </div>
            <!-- Ações -->
            <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
              <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base hover:bg-[#F1F5F9] dark:hover:bg-[#334155] transition-all rounded-lg">
                Cancelar
              </Button>
              <Button variant="destructive" @click="confirmDeleteFornecedor" class="px-7 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
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

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import { Edit, Eye, Plus, Trash2 } from 'lucide-vue-next';

interface Endereco {
  id: number;
  linha1: string;
  linha2?: string;
  cidade: string;
  estado: string;
  cep?: string;
  pais?: string;
}

interface Fornecedor {
  id: number;
  nome: string;
  documento?: string;
  email?: string;
  telefone?: string;
  endereco_id: number;
  observacoes?: string;
  endereco?: Endereco;
  created_at: string;
  updated_at: string;
}

interface Props {
  fornecedores: {
    data: Fornecedor[];
  };
}

const props = defineProps<Props>();

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Fornecedores', href: '/fornecedores' },
];

const showDeleteDialog = ref(false);
const fornecedorToDelete = ref<Fornecedor | null>(null);
const toast = useToast();

function askDeleteFornecedor(fornecedor: Fornecedor) {
  fornecedorToDelete.value = fornecedor;
  showDeleteDialog.value = true;
}

function confirmDeleteFornecedor() {
  if (fornecedorToDelete.value) {
    router.delete(`/fornecedores/${fornecedorToDelete.value.id}`, {
      onSuccess: () => {
        showDeleteDialog.value = false;
        fornecedorToDelete.value = null;
        toast.add({
          severity: 'success',
          summary: 'Sucesso',
          detail: 'Fornecedor removido com sucesso!',
          life: 4000,
          group: 'main',
        });
      },
    });
  }
}
</script>

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
