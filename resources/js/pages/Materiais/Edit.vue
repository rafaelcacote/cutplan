<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import MaterialForm from './Form.vue';

const props = defineProps({
    material: Object,
    tiposmaterial: Array,
    unidades: Array,
    fornecedores: Array,
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Materiais', href: '/materiais' },
    { title: `Editar Material`, href: `/materiais/${props.material.id}/edit` },
];

const form = useForm({
    sku: props.material.sku,
    nome: props.material.nome,
    tipo_id: props.material.tipo_id,
    unidade_id: props.material.unidade_id,
    fornecedor_padrao_id: props.material.fornecedor_padrao_id,
    preco_custo: props.material.preco_custo,
    estoque_minimo: props.material.estoque_minimo,
    controla_estoque: props.material.controla_estoque,
    ativo: props.material.ativo,
});

function handleSubmit() {
    form.put(route('materiais.update', props.material.id));
}
</script>

<template>
    <Head title="Editar Material" />
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
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Editar Material</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Edite as informações do material</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="handleSubmit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Editar Material</CardTitle>
                        <CardDescription>Edite os dados do material abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <MaterialForm
                            v-model="form"
                            :tiposmaterial="props.tiposmaterial"
                            :unidades="props.unidades"
                            :fornecedores="props.fornecedores"
                        />
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
