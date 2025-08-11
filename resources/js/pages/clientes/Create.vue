<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';



import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

interface Endereco {
    id: number;
    linha1: string;
    linha2?: string;
    cidade: string;
    estado: string;
    cep?: string;
    pais?: string;
}



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
        title: 'Novo Cliente',
        href: '/clientes/create',
    },
];

const form = useForm({
    nome: '',
    documento: '',
    email: '',
    telefone: '',
    observacoes: '',
    endereco: {
        linha1: '',
        linha2: '',
        cidade: '',
        estado: '',
        cep: '',
        pais: '',
    },
});

const submit = () => {
    form.post(route('clientes.store'), {
        onSuccess: () => {
            // O redirect será feito pelo backend
        }
    });
};
</script>

<template>
    <Head title="Novo Cliente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col min-h-[calc(100vh-64px)] w-full bg-background p-0">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 px-8 pt-8 pb-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('clientes.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Novo Cliente</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Cadastre um novo cliente</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="submit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Novo Cliente</CardTitle>
                        <CardDescription>Preencha os dados do cliente abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="nome">Nome</Label>
                                    <InputText id="nome" v-model="form.nome" placeholder="Digite o nome do cliente" class="w-full" />
                                    <p v-if="form.errors.nome" class="text-sm text-destructive">{{ form.errors.nome }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="documento">Documento</Label>
                                    <InputText id="documento" v-model="form.documento" placeholder="CPF/CNPJ" class="w-full" />
                                    <p v-if="form.errors.documento" class="text-sm text-destructive">{{ form.errors.documento }}</p>
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
                            </div>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="linha1">Endereço</Label>
                                    <InputText id="linha1" v-model="form.endereco.linha1" placeholder="Rua, número" class="w-full" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="linha2">Complemento</Label>
                                    <InputText id="linha2" v-model="form.endereco.linha2" placeholder="Apto, bloco, etc." class="w-full" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <Label for="cidade">Cidade</Label>
                                        <InputText id="cidade" v-model="form.endereco.cidade" placeholder="Cidade" class="w-full" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="estado">Estado</Label>
                                        <InputText id="estado" v-model="form.endereco.estado" placeholder="Estado" class="w-full" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="cep">CEP</Label>
                                        <InputText id="cep" v-model="form.endereco.cep" placeholder="CEP" class="w-full" />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="pais">País</Label>
                                    <InputText id="pais" v-model="form.endereco.pais" placeholder="País" class="w-full" />
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2 col-span-1 space-y-2 mt-6">
                            <Label for="observacoes">Observações</Label>
                            <Textarea id="observacoes" v-model="form.observacoes" placeholder="Observações sobre o cliente" class="w-full min-h-[90px]" autoResize />
                            <p v-if="form.errors.observacoes" class="text-sm text-destructive">{{ form.errors.observacoes }}</p>
                        </div>
                        <div class="flex items-center gap-4 justify-end mt-8">
                            <Button type="submit" :disabled="form.processing" size="lg" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-8 py-3 text-lg font-semibold transition-transform duration-300 hover:scale-105">
                                <Save class="mr-2 h-5 w-5" />
                                {{ form.processing ? 'Salvando...' : 'Salvar' }}
                            </Button>
                            <Button variant="outline" type="button" as-child size="lg" class="px-8 py-3 text-lg border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                                <Link :href="route('clientes.index')"> Cancelar </Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
