<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import AutoComplete from 'primevue/autocomplete';
import InputText from 'primevue/inputtext';

interface Cliente { id: number; nome: string; }
interface Fornecedor { id: number; nome: string; }
interface Props {
  contato: {
    id: number;
    nome: string;
    email?: string;
    telefone?: string;
    cargo?: string;
    cliente?: Cliente|null;
    fornecedor?: Fornecedor|null;
  };
}
const props = defineProps<Props>();
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Contatos', href: '/contatos' },
  { title: 'Editar Contato', href: `/contatos/${props.contato.id}/edit` },
];
const form = useForm({
  nome: props.contato.nome,
  email: props.contato.email || '',
  telefone: props.contato.telefone || '',
  cargo: props.contato.cargo || '',
  cliente_id: props.contato.cliente?.id || null,
  fornecedor_id: props.contato.fornecedor?.id || null,
});
const cliente = ref<Cliente|null>(props.contato.cliente || null);
const fornecedor = ref<Fornecedor|null>(props.contato.fornecedor || null);
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
watch(cliente, (val) => { form.cliente_id = val?.id || null; });
watch(fornecedor, (val) => { form.fornecedor_id = val?.id || null; });
const submit = () => {
  form.put(route('contatos.update', props.contato.id));
};
</script>
<template>
  <Head title="Editar Contato" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col min-h-[calc(100vh-64px)] w-full bg-background p-0">
      <div class="flex items-center gap-4 px-8 pt-8 pb-4">
        <Button variant="ghost" size="sm" as-child>
          <Link :href="route('contatos.index')">
            <ArrowLeft class="h-4 w-4" />
          </Link>
        </Button>
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Editar Contato</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Edite as informações do contato</p>
        </div>
      </div>
      <form @submit.prevent="submit" class="flex-1 px-8 pb-8">
        <Card class="p-8">
          <CardHeader>
            <CardTitle>Editar Contato</CardTitle>
            <CardDescription>Edite os dados do contato abaixo</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="space-y-6">
                <div class="space-y-2">
                  <Label for="nome">Nome</Label>
                  <InputText id="nome" v-model="form.nome" placeholder="Digite o nome do contato" class="w-full" />
                  <p v-if="form.errors.nome" class="text-sm text-destructive">{{ form.errors.nome }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="email">Email</Label>
                  <InputText id="email" v-model="form.email" placeholder="email@exemplo.com" class="w-full" />
                  <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="telefone">Telefone</Label>
                  <InputText id="telefone" v-model="form.telefone" placeholder="(11) 99999-9999" class="w-full" />
                  <p v-if="form.errors.telefone" class="text-sm text-destructive">{{ form.errors.telefone }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="cargo">Cargo</Label>
                  <InputText id="cargo" v-model="form.cargo" placeholder="Cargo do contato" class="w-full" />
                  <p v-if="form.errors.cargo" class="text-sm text-destructive">{{ form.errors.cargo }}</p>
                </div>
              </div>
              <div class="space-y-6">
                <div class="space-y-2">
                  <Label for="cliente">Cliente</Label>
                  <AutoComplete v-model="cliente" :suggestions="clienteSuggestions" field="nome" optionLabel="nome" @complete="searchClientes" placeholder="Buscar cliente" class="w-full">
                    <template #option="{ option }">
                      <span>{{ option.nome }}</span>
                    </template>
                    <template #chip="{ value }">
                      <span>{{ value.nome }}</span>
                    </template>
                  </AutoComplete>
                  <p v-if="form.errors.cliente_id" class="text-sm text-destructive">{{ form.errors.cliente_id }}</p>
                </div>
                <div class="space-y-2">
                  <Label for="fornecedor">Fornecedor</Label>
                  <AutoComplete v-model="fornecedor" :suggestions="fornecedorSuggestions" field="nome" optionLabel="nome" @complete="searchFornecedores" placeholder="Buscar fornecedor" class="w-full">
                    <template #option="{ option }">
                      <span>{{ option.nome }}</span>
                    </template>
                    <template #chip="{ value }">
                      <span>{{ value.nome }}</span>
                    </template>
                  </AutoComplete>
                  <p v-if="form.errors.fornecedor_id" class="text-sm text-destructive">{{ form.errors.fornecedor_id }}</p>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-4 justify-end mt-8">
              <Button type="submit" :disabled="form.processing" size="lg" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-8 py-3 text-lg font-semibold transition-transform duration-300 hover:scale-105">
                <Save class="mr-2 h-5 w-5" />
                {{ form.processing ? 'Salvando...' : 'Salvar' }}
              </Button>
              <Button variant="outline" type="button" as-child size="lg" class="px-8 py-3 text-lg border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                <Link :href="route('contatos.index')"> Cancelar </Link>
              </Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </div>
  </AppLayout>
</template>
