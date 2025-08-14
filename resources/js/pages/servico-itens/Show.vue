<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import Tag from 'primevue/tag';
import { ArrowLeft, Edit, Clock, Hash, CheckCircle2, AlertCircle } from 'lucide-vue-next';

interface Servico {
    id: number;
    nome: string;
    descricao?: string;
    categoria?: string;
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
    servicoItem: ServicoItem;
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
    {
        title: 'Visualizar Item',
        href: `/servico-itens/${props.servicoItem.id}`,
    },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
  <Head title="Visualizar Item de Serviço" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Item de Serviço</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Visualize os detalhes do item</p>
        </div>
        <div class="flex gap-3">
          <Button variant="outline" as-child class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
            <Link :href="route('servico-itens.index')">
              <ArrowLeft class="mr-2 h-4 w-4" />
              Voltar
            </Link>
          </Button>
          <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold transition-transform duration-300 hover:scale-105">
            <Link :href="route('servico-itens.edit', servicoItem.id)">
              <Edit class="mr-2 h-4 w-4" />
              Editar
            </Link>
          </Button>
        </div>
      </div>

      <!-- Informações Principais -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader class="pb-4">
          <div class="flex items-center justify-between">
            <div>
              <CardTitle class="text-2xl text-[#1E293B] dark:text-white">{{ servicoItem.descricao_item }}</CardTitle>
              <CardDescription class="text-[#64748B] dark:text-[#CBD5E1] mt-2">
                Item do serviço: <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ servicoItem.servico.nome }}</span>
              </CardDescription>
            </div>
            <Tag 
              :value="servicoItem.opcional ? 'Opcional' : 'Obrigatório'"
              :severity="servicoItem.opcional ? 'info' : 'success'"
              class="text-sm px-3 py-1"
            >
              <template #icon>
                <component 
                  :is="servicoItem.opcional ? AlertCircle : CheckCircle2" 
                  class="w-4 h-4 mr-1"
                />
              </template>
            </Tag>
          </div>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Ordem -->
            <div class="space-y-2">
              <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                <Hash class="w-4 h-4" />
                Ordem de Execução
              </div>
              <div class="text-2xl font-bold text-[#1E293B] dark:text-white">
                {{ servicoItem.ordem || 'Não definida' }}
              </div>
            </div>

            <!-- Tipo -->
            <div class="space-y-2">
              <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                <component :is="servicoItem.opcional ? AlertCircle : CheckCircle2" class="w-4 h-4" />
                Tipo do Item
              </div>
              <div class="text-2xl font-bold text-[#1E293B] dark:text-white">
                {{ servicoItem.opcional ? 'Opcional' : 'Obrigatório' }}
              </div>
            </div>

            <!-- Data de Criação -->
            <div class="space-y-2">
              <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                <Clock class="w-4 h-4" />
                Criado em
              </div>
              <div class="text-lg font-semibold text-[#1E293B] dark:text-white">
                {{ formatDate(servicoItem.created_at) }}
              </div>
            </div>

            <!-- Última Atualização -->
            <div class="space-y-2">
              <div class="flex items-center gap-2 text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">
                <Clock class="w-4 h-4" />
                Atualizado em
              </div>
              <div class="text-lg font-semibold text-[#1E293B] dark:text-white">
                {{ formatDate(servicoItem.updated_at) }}
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Descrição Detalhada -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Descrição do Item</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Detalhamento completo deste item do serviço
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="bg-[#F8FAFC] dark:bg-[#334155] rounded-lg p-6 border border-[#E0E7EF] dark:border-[#475569]">
            <p class="text-[#1E293B] dark:text-white leading-relaxed whitespace-pre-wrap">{{ servicoItem.descricao_item }}</p>
          </div>
        </CardContent>
      </Card>

      <!-- Informações do Serviço -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Serviço Relacionado</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Informações do serviço ao qual este item pertence
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="bg-gradient-to-r from-[#3B82F6]/5 to-[#6366F1]/5 dark:from-[#3B82F6]/10 dark:to-[#6366F1]/10 rounded-lg p-6 border border-[#3B82F6]/20">
            <div class="flex items-start justify-between">
              <div class="space-y-3">
                <h3 class="text-xl font-bold text-[#3B82F6] dark:text-[#60A5FA]">{{ servicoItem.servico.nome }}</h3>
                <div v-if="servicoItem.servico.descricao" class="text-[#64748B] dark:text-[#CBD5E1]">
                  {{ servicoItem.servico.descricao }}
                </div>
                <div v-if="servicoItem.servico.categoria" class="flex items-center gap-2">
                  <Tag 
                    :value="servicoItem.servico.categoria"
                    severity="info"
                    class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA]"
                  />
                </div>
              </div>
              <Button variant="outline" as-child class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                <Link :href="route('servicos.show', servicoItem.servico.id)">
                  Ver Serviço
                </Link>
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Observações do Sistema -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Informações do Sistema</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Dados técnicos e de controle
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
              <div class="text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">ID do Item</div>
              <div class="font-mono text-[#1E293B] dark:text-white">#{{ servicoItem.id }}</div>
            </div>
            <div class="space-y-1">
              <div class="text-sm font-medium text-[#64748B] dark:text-[#CBD5E1]">ID do Serviço</div>
              <div class="font-mono text-[#1E293B] dark:text-white">#{{ servicoItem.servico_id }}</div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
