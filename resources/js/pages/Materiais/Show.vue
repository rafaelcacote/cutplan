<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    material: Object,
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Materiais', href: '/materiais' },
    { title: `Material #${props.material.id}`, href: `/materiais/${props.material.id}` },
];

function handleDelete() {
    if (confirm('Tem certeza que deseja remover este material?')) {
        router.delete(route('materiais.destroy', props.material.id));
    }
}
</script>

<template>
    <Head title="Visualizar Material" />
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
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Material #{{ props.material.id }}</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Detalhes do material</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button as-child variant="outline">
                        <Link :href="route('materiais.edit', props.material.id)"> <Edit class="mr-2 h-4 w-4" /> Editar </Link>
                    </Button>
                    <Button variant="destructive" @click="handleDelete"> <Trash2 class="mr-2 h-4 w-4" /> Remover </Button>
                </div>
            </div>
            <Card class="p-8">
                <CardHeader>
                    <CardTitle>Dados do Material</CardTitle>
                    <CardDescription>Informações detalhadas do material</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="space-y-4">
                            <div><strong>SKU:</strong> {{ props.material.sku }}</div>
                            <div><strong>Nome:</strong> {{ props.material.nome }}</div>
                            <div><strong>Tipo:</strong> {{ props.material.tipo?.nome || '-' }}</div>
                            <div><strong>Unidade:</strong> {{ props.material.unidade?.nome || '-' }}</div>
                            <div><strong>Fornecedor Padrão:</strong> {{ props.material.fornecedorPadrao?.nome || '-' }}</div>
                        </div>
                        <div class="space-y-4">
                            <div><strong>Preço de Custo:</strong> {{ props.material.preco_custo }}</div>
                            <div><strong>Estoque Mínimo:</strong> {{ props.material.estoque_minimo }}</div>
                            <div><strong>Controla Estoque:</strong> {{ props.material.controla_estoque ? 'Sim' : 'Não' }}</div>
                            <div>
                                <strong>Ativo:</strong>
                                <span :class="props.material.ativo ? 'text-green-600' : 'text-red-500'">{{
                                    props.material.ativo ? 'Sim' : 'Não'
                                }}</span>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
