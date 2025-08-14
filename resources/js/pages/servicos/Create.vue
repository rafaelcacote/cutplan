<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Serviços', href: '/servicos' },
    { title: 'Novo Serviço', href: '/servicos/create' },
];

const form = useForm({
    nome: '',
    descricao: '',
    preco_base: '',
    categoria: '',
    ativo: true,
});

const submit = () => {
    form.post(route('servicos.store'), {
        onSuccess: () => {
            // O redirect será feito pelo backend
        }
    });
};
</script>
<template>
    <Head title="Novo Serviço" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col min-h-[calc(100vh-64px)] w-full bg-background p-0">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 px-8 pt-8 pb-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('servicos.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Novo Serviço</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Cadastre um novo serviço</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="submit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Novo Serviço</CardTitle>
                        <CardDescription>Preencha os dados do serviço abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="nome">Nome</Label>
                                    <InputText id="nome" v-model="form.nome" placeholder="Digite o nome do serviço" class="w-full" />
                                    <p v-if="form.errors.nome" class="text-sm text-destructive">{{ form.errors.nome }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="categoria">Categoria</Label>
                                    <InputText id="categoria" v-model="form.categoria" placeholder="Categoria do serviço" class="w-full" />
                                    <p v-if="form.errors.categoria" class="text-sm text-destructive">{{ form.errors.categoria }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="preco_base">Preço Base</Label>
                                    <InputNumber id="preco_base" v-model="form.preco_base" mode="currency" currency="BRL" locale="pt-BR" class="w-full" />
                                    <p v-if="form.errors.preco_base" class="text-sm text-destructive">{{ form.errors.preco_base }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="ativo">Status</Label>
                                    <Dropdown id="ativo" v-model="form.ativo" :options="[{label: 'Ativo', value: true}, {label: 'Inativo', value: false}]" option-label="label" option-value="value" class="w-full" />
                                    <p v-if="form.errors.ativo" class="text-sm text-destructive">{{ form.errors.ativo }}</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="descricao">Descrição</Label>
                                    <Textarea id="descricao" v-model="form.descricao" placeholder="Descrição do serviço" class="w-full" rows="8" />
                                    <p v-if="form.errors.descricao" class="text-sm text-destructive">{{ form.errors.descricao }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-8 gap-4">
                            <Button variant="outline" type="button" as-child size="lg" class="px-8 py-3 text-lg border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                                <Link :href="route('servicos.index')">Cancelar</Link>
                            </Button>
                            <Button type="submit" size="lg" class="px-8 py-3 text-lg bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white rounded-lg font-semibold shadow-lg border-0 transition-transform duration-300 hover:scale-105">
                                <Save class="mr-2 h-5 w-5" />
                                Salvar
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
