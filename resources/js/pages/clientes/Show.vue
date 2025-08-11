<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
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

interface Cliente {
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
    cliente: Cliente;
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
    {
        title: `Cliente #${props.cliente.id}`,
        href: `/clientes/${props.cliente.id}`,
    },
];

const deleteCliente = () => {
    if (confirm(`Tem certeza que deseja excluir o cliente "${props.cliente.nome}"?`)) {
        router.delete(route('clientes.destroy', props.cliente.id));
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const formatDateTime = (datetime: string) => {
    return new Date(datetime).toLocaleString('pt-BR');
};
</script>

<template>
    <Head :title="`Cliente #${cliente.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300 rounded-xl">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('clientes.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">{{ cliente.nome }}</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Detalhes do cliente</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-6 py-2 rounded-lg font-semibold text-base transition-transform duration-300 hover:scale-105">
                        <Link :href="route('clientes.edit', cliente.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                    <Button variant="destructive" @click="deleteCliente" class="px-6 py-2 text-base flex items-center gap-2 shadow hover:bg-red-600 hover:text-white transition-all rounded-lg">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Excluir
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações do Cliente</CardTitle>
                        <CardDescription> Dados principais do cliente </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Nome</Label>
                            <p class="text-lg font-medium">{{ cliente.nome }}</p>
                        </div>
                        <div v-if="cliente.documento">
                            <Label class="text-sm font-medium text-muted-foreground">Documento</Label>
                            <p class="text-lg">{{ cliente.documento }}</p>
                        </div>
                        <div v-if="cliente.email">
                            <Label class="text-sm font-medium text-muted-foreground">Email</Label>
                            <p class="text-lg">{{ cliente.email }}</p>
                        </div>
                        <div v-if="cliente.telefone">
                            <Label class="text-sm font-medium text-muted-foreground">Telefone</Label>
                            <p class="text-lg">{{ cliente.telefone }}</p>
                        </div>
                        <div v-if="cliente.observacoes">
                            <Label class="text-sm font-medium text-muted-foreground">Observações</Label>
                            <p class="text-lg">{{ cliente.observacoes }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Endereço</CardTitle>
                        <CardDescription> Endereço do cliente </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Endereço</Label>
                            <p class="text-lg">
                                {{ cliente.endereco.linha1 }}
                                <span v-if="cliente.endereco.linha2">, {{ cliente.endereco.linha2 }}</span>
                            </p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Cidade</Label>
                            <p class="text-lg">{{ cliente.endereco.cidade }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Estado</Label>
                            <p class="text-lg">{{ cliente.endereco.estado }}</p>
                        </div>
                        <div v-if="cliente.endereco.cep">
                            <Label class="text-sm font-medium text-muted-foreground">CEP</Label>
                            <p class="text-lg">{{ cliente.endereco.cep }}</p>
                        </div>
                        <div v-if="cliente.endereco.pais">
                            <Label class="text-sm font-medium text-muted-foreground">País</Label>
                            <p class="text-lg">{{ cliente.endereco.pais }}</p>
                        </div>
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
                        <p class="text-lg">{{ formatDateTime(cliente.created_at) }}</p>
                    </div>
                    <div>
                        <Label class="text-sm font-medium text-muted-foreground">Última atualização</Label>
                        <p class="text-lg">{{ formatDateTime(cliente.updated_at) }}</p>
                    </div>
                    <div v-if="cliente.user">
                        <Label class="text-sm font-medium text-muted-foreground">Cadastrado por</Label>
                        <p class="text-lg">{{ cliente.user.name }}</p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
