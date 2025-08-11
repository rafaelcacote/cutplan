<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

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
        title: 'Nova Forma de Pagamento',
        href: '/formas-pagamento/create',
    },
];

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('formas-pagamento.store'));
};
</script>

<template>
    <Head title="Nova Forma de Pagamento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('formas-pagamento.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Nova Forma de Pagamento</h1>
                    <p class="text-muted-foreground">Crie uma nova forma de pagamento</p>
                </div>
            </div>

            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Informações da Forma de Pagamento</CardTitle>
                    <CardDescription> Preencha os dados abaixo para criar uma nova forma de pagamento </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nome da Forma de Pagamento</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Digite o nome da forma de pagamento"
                                :class="{ 'border-destructive': form.errors.name }"
                                required
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button type="submit" :disabled="form.processing">
                                <Save class="mr-2 h-4 w-4" />
                                {{ form.processing ? 'Salvando...' : 'Salvar' }}
                            </Button>
                            <Button variant="outline" type="button" as-child>
                                <Link :href="route('formas-pagamento.index')"> Cancelar </Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
