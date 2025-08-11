<template>
  <Head :title="`Fornecedor #${fornecedor.id}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300 rounded-xl">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <Button variant="ghost" size="sm" as-child>
            <Link :href="route('fornecedores.index')">
              <ArrowLeft class="h-4 w-4" />
            </Link>
          </Button>
          <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">{{ fornecedor.nome }}</h1>
            <p class="text-[#64748B] dark:text-[#CBD5E1]">Detalhes do fornecedor</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
            <Link :href="route('fornecedores.edit', fornecedor.id)">
              <Edit class="mr-2 h-4 w-4" />
              Editar
            </Link>
          </Button>
          <Button variant="destructive" @click="askDeleteFornecedor" class="px-6 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
            <Trash2 class="mr-2 h-4 w-4" />Excluir
          </Button>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle>Informações do Fornecedor</CardTitle>
            <CardDescription> Dados principais do fornecedor </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <div>
              <Label class="text-sm font-medium text-muted-foreground">Nome</Label>
              <p class="text-lg font-medium">{{ fornecedor.nome }}</p>
            </div>
            <div v-if="fornecedor.documento">
              <Label class="text-sm font-medium text-muted-foreground">Documento</Label>
              <p class="text-lg">{{ fornecedor.documento }}</p>
            </div>
            <div v-if="fornecedor.email">
              <Label class="text-sm font-medium text-muted-foreground">Email</Label>
              <p class="text-lg">{{ fornecedor.email }}</p>
            </div>
            <div v-if="fornecedor.telefone">
              <Label class="text-sm font-medium text-muted-foreground">Telefone</Label>
              <p class="text-lg">{{ fornecedor.telefone }}</p>
            </div>
            <div v-if="fornecedor.observacoes">
              <Label class="text-sm font-medium text-muted-foreground">Observações</Label>
              <p class="text-lg">{{ fornecedor.observacoes }}</p>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Endereço</CardTitle>
            <CardDescription> Endereço do fornecedor </CardDescription>
          </CardHeader>
          <CardContent class="space-y-4">
            <div v-if="fornecedor.endereco">
              <Label class="text-sm font-medium text-muted-foreground">Endereço</Label>
              <p class="text-lg">
                {{ fornecedor.endereco.linha1 }}
                <span v-if="fornecedor.endereco.linha2">, {{ fornecedor.endereco.linha2 }}</span>
              </p>
            </div>
            <div v-if="fornecedor.endereco">
              <Label class="text-sm font-medium text-muted-foreground">Cidade</Label>
              <p class="text-lg">{{ fornecedor.endereco.cidade }}</p>
            </div>
            <div v-if="fornecedor.endereco">
              <Label class="text-sm font-medium text-muted-foreground">Estado</Label>
              <p class="text-lg">{{ fornecedor.endereco.estado }}</p>
            </div>
            <div v-if="fornecedor.endereco && fornecedor.endereco.cep">
              <Label class="text-sm font-medium text-muted-foreground">CEP</Label>
              <p class="text-lg">{{ fornecedor.endereco.cep }}</p>
            </div>
            <div v-if="fornecedor.endereco && fornecedor.endereco.pais">
              <Label class="text-sm font-medium text-muted-foreground">País</Label>
              <p class="text-lg">{{ fornecedor.endereco.pais }}</p>
            </div>
            <div v-if="!fornecedor.endereco" class="text-[#CBD5E1] dark:text-[#64748B]">Não informado</div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Informações do Sistema</CardTitle>
          <CardDescription> Dados de controle do sistema </CardDescription>
        </CardHeader>
        <CardContent class="grid gap-4 md:grid-cols-2">
          <div>
            <Label class="text-sm font-medium text-muted-foreground">Criado em</Label>
            <p class="text-lg">{{ formatDateTime(fornecedor.created_at) }}</p>
          </div>
          <div>
            <Label class="text-sm font-medium text-muted-foreground">Última atualização</Label>
            <p class="text-lg">{{ formatDateTime(fornecedor.updated_at) }}</p>
          </div>
          <div v-if="fornecedor.user">
            <Label class="text-sm font-medium text-muted-foreground">Cadastrado por</Label>
            <p class="text-lg">{{ fornecedor.user.name }}</p>
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
                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ fornecedor.nome }}</span>?
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
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Dialog from 'primevue/dialog';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

interface Endereco {
    id: number;
    linha1: string;
    linha2?: string;
    cidade: string;
    estado: string;
    cep?: string;
    pais?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Fornecedor {
    id: number;
    nome: string;
    documento?: string;
    email?: string;
    telefone?: string;
    endereco_id: number;
    observacoes?: string;
    endereco: Endereco;
    user?: User;
    created_at: string;
    updated_at: string;
}

interface Props {
    fornecedor: Fornecedor;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Fornecedores',
        href: '/fornecedores',
    },
    {
        title: `Fornecedor #${props.fornecedor.id}`,
        href: `/fornecedores/${props.fornecedor.id}`,
    },
];

const showDeleteDialog = ref(false);

const askDeleteFornecedor = () => {
    showDeleteDialog.value = true;
};

const confirmDeleteFornecedor = () => {
    router.delete(route('fornecedores.destroy', props.fornecedor.id), {
        onSuccess: () => {
            showDeleteDialog.value = false;
        }
    });
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const formatDateTime = (datetime: string) => {
    return new Date(datetime).toLocaleString('pt-BR');
};
</script>
