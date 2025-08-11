<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

interface FormaPagamento {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    formaPagamento: FormaPagamento;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Formas de Pagamento',
        href: '/formas-pagamento',
    },
    {
        title: props.formaPagamento.name,
        href: `/formas-pagamento/${props.formaPagamento.id}`,
    },
];

const deleteFormaPagamento = () => {
    if (confirm(`Tem certeza que deseja excluir a forma de pagamento "${props.formaPagamento.name}"?`)) {
        router.delete(route('formas-pagamento.destroy', props.formaPagamento.id));
    }
};
</script>

<template>
    <Head :title="formaPagamento.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('formas-pagamento.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold tracking-tight">{{ formaPagamento.name }}</h1>
                    <p class="text-muted-foreground">Detalhes da forma de pagamento</p>
                </div>
                <div class="flex items-center gap-2">
                    <Button as-child>
                        <Link :href="route('formas-pagamento.edit', formaPagamento.id)">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar
                        </Link>
                    </Button>
                    <Button variant="destructive" @click="deleteFormaPagamento">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Excluir
                    </Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Básicas</CardTitle>
                        <CardDescription> Dados principais da forma de pagamento </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Nome</Label>
                            <p class="text-lg font-medium">{{ formaPagamento.name }}</p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">ID</Label>
                            <p class="text-sm">{{ formaPagamento.id }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Datas</CardTitle>
                        <CardDescription> Informações de criação e atualização </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Criado em</Label>
                            <p class="text-sm">
                                {{ new Date(formaPagamento.created_at).toLocaleString('pt-BR') }}
                            </p>
                        </div>
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Atualizado em</Label>
                            <p class="text-sm">
                                {{ new Date(formaPagamento.updated_at).toLocaleString('pt-BR') }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
