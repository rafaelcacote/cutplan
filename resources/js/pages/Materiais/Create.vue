<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import MaterialForm from './Form.vue';

const props = defineProps({
    tipos: Array,
    unidades: Array,
    fornecedores: Array,
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Materiais', href: '/materiais' },
    { title: 'Novo Material', href: '/materiais/create' },
];

const form = useForm({
    sku: '',
    nome: '',
    tipo_id: null,
    unidade_id: null,
    fornecedor_padrao_id: null,
    preco_custo: '',
    estoque_minimo: '',
    controla_estoque: 1,
    ativo: 1,
});

function handleSubmit() {
    form.post(route('materiais.store'));
}
</script>

<template>
    <Head title="Novo Material" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-[calc(100vh-64px)] w-full flex-col bg-background p-0">
            <div class="flex flex-col items-start justify-between gap-4 px-8 pt-8 pb-4 md:flex-row md:items-center">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('materiais.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Novo Material</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Cadastre um novo material</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="handleSubmit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Novo Material</CardTitle>
                        <CardDescription>Preencha os dados do material abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <MaterialForm v-model="form" :tipos="props.tipos" :unidades="props.unidades" :fornecedores="props.fornecedores" />
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
