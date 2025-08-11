<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

interface Category {
    id: number;
    nome: string;
}

interface FormaPagamento {
    id: number;
    nome: string;
}

interface Gasto {
    id: number;
    data_gasto: string;
    valor: string;
    descricao: string;
    categoria_id: number;
    forma_pagamento_id: number;
}

interface Props {
    gasto: Gasto;
    categorias: Category[];
    formasPagamento: FormaPagamento[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Gastos',
        href: '/gastos',
    },
    {
        title: 'Editar Gasto',
        href: `/gastos/${props.gasto.id}/edit`,
    },
];

const form = useForm({
    data_gasto: props.gasto.data_gasto,
    valor: props.gasto.valor,
    descricao: props.gasto.descricao,
    categoria_id: props.gasto.categoria_id.toString(),
    forma_pagamento_id: props.gasto.forma_pagamento_id.toString(),
});

const submit = () => {
    form.put(route('gastos.update', props.gasto.id));
};
</script>

<template>
    <Head title="Editar Gasto" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('gastos.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Editar Gasto</h1>
                    <p class="text-muted-foreground">Edite as informações do gasto</p>
                </div>
            </div>

            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Informações do Gasto</CardTitle>
                    <CardDescription> Edite os dados abaixo para atualizar o gasto </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="data_gasto">Data do Gasto</Label>
                                <Input
                                    id="data_gasto"
                                    v-model="form.data_gasto"
                                    type="date"
                                    :class="{ 'border-destructive': form.errors.data_gasto }"
                                    required
                                />
                                <p v-if="form.errors.data_gasto" class="text-sm text-destructive">
                                    {{ form.errors.data_gasto }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="valor">Valor</Label>
                                <Input
                                    id="valor"
                                    v-model="form.valor"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    placeholder="0,00"
                                    :class="{ 'border-destructive': form.errors.valor }"
                                    required
                                />
                                <p v-if="form.errors.valor" class="text-sm text-destructive">
                                    {{ form.errors.valor }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="descricao">Descrição</Label>
                            <Input
                                id="descricao"
                                v-model="form.descricao"
                                type="text"
                                placeholder="Descreva o gasto"
                                :class="{ 'border-destructive': form.errors.descricao }"
                                required
                            />
                            <p v-if="form.errors.descricao" class="text-sm text-destructive">
                                {{ form.errors.descricao }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="categoria_id">Categoria</Label>
                                <select
                                    id="categoria_id"
                                    v-model="form.categoria_id"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    :class="{ 'border-destructive': form.errors.categoria_id }"
                                    required
                                >
                                    <option value="">Selecione uma categoria</option>
                                    <option
                                        v-for="categoria in categorias"
                                        :key="categoria.id"
                                        :value="categoria.id"
                                    >
                                        {{ categoria.nome }}
                                    </option>
                                </select>
                                <p v-if="form.errors.categoria_id" class="text-sm text-destructive">
                                    {{ form.errors.categoria_id }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="forma_pagamento_id">Forma de Pagamento</Label>
                                <select
                                    id="forma_pagamento_id"
                                    v-model="form.forma_pagamento_id"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    :class="{ 'border-destructive': form.errors.forma_pagamento_id }"
                                    required
                                >
                                    <option value="">Selecione uma forma de pagamento</option>
                                    <option
                                        v-for="formaPagamento in formasPagamento"
                                        :key="formaPagamento.id"
                                        :value="formaPagamento.id"
                                    >
                                        {{ formaPagamento.nome }}
                                    </option>
                                </select>
                                <p v-if="form.errors.forma_pagamento_id" class="text-sm text-destructive">
                                    {{ form.errors.forma_pagamento_id }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button type="submit" :disabled="form.processing">
                                <Save class="mr-2 h-4 w-4" />
                                {{ form.processing ? 'Salvando...' : 'Salvar' }}
                            </Button>
                            <Button variant="outline" type="button" as-child>
                                <Link :href="route('gastos.index')"> Cancelar </Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
