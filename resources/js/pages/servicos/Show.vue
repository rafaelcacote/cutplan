<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

type Servico = {
    id: number;
    nome: string;
    descricao?: string;
    preco_base?: string;
    categoria?: string;
    ativo: boolean;
    created_at: string;
    updated_at: string;
};
interface Props {
    servico: Servico;
}
const props = defineProps<Props>();
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Serviços', href: '/servicos' },
    { title: `Serviço #${props.servico.id}`, href: `/servicos/${props.servico.id}` },
];
const deleteServico = () => {
    if (confirm(`Tem certeza que deseja excluir o serviço "${props.servico.nome}"?`)) {
        router.delete(route('servicos.destroy', props.servico.id));
    }
};
const formatDate = (date: string) => new Date(date).toLocaleDateString('pt-BR');
const formatDateTime = (datetime: string) => new Date(datetime).toLocaleString('pt-BR');
</script>
<template>
    <Head :title="`Serviço #${props.servico.id}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300 rounded-xl">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('servicos.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">{{ props.servico.nome }}</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Detalhes do serviço</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                        <Link :href="route('servicos.edit', props.servico.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                    <Button variant="destructive" @click="deleteServico" class="px-6 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Excluir
                    </Button>
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Serviço</CardTitle>
                        <CardDescription>Dados principais do serviço</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Nome</Label>
                            <p class="text-lg font-medium">{{ props.servico.nome }}</p>
                        </div>
                        <div v-if="props.servico.categoria">
                            <Label class="text-sm font-medium text-muted-foreground">Categoria</Label>
                            <p class="text-lg">{{ props.servico.categoria }}</p>
                        </div>
                        <div v-if="props.servico.preco_base">
                            <Label class="text-sm font-medium text-muted-foreground">Preço Base</Label>
                            <p class="text-lg">R$ {{ parseFloat(props.servico.preco_base).toFixed(2) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Status</Label>
                            <p class="text-lg" :class="props.servico.ativo ? 'text-green-600' : 'text-red-500'">{{ props.servico.ativo ? 'Ativo' : 'Inativo' }}</p>
                        </div>
                        <div v-if="props.servico.descricao">
                            <Label class="text-sm font-medium text-muted-foreground">Descrição</Label>
                            <p class="text-lg">{{ props.servico.descricao }}</p>
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Sistema</CardTitle>
                        <CardDescription>Dados de controle do sistema</CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 md:grid-cols-2">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Criado em</Label>
                            <p class="text-lg">{{ formatDateTime(props.servico.created_at) }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Última atualização</Label>
                            <p class="text-lg">{{ formatDateTime(props.servico.updated_at) }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
