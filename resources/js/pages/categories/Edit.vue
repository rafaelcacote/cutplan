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
    name: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    category: Category;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Categorias',
        href: '/categories',
    },
    {
        title: props.category.name,
        href: `/categories/${props.category.id}`,
    },
    {
        title: 'Editar',
        href: `/categories/${props.category.id}/edit`,
    },
];

const form = useForm({
    name: props.category.name,
});

const submit = () => {
    form.put(route('categories.update', props.category.id));
};
</script>

<template>
    <Head :title="`Editar ${category.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('categories.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Editar Categoria</h1>
                    <p class="text-muted-foreground">Edite as informações da categoria "{{ category.name }}"</p>
                </div>
            </div>

            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Informações da Categoria</CardTitle>
                    <CardDescription> Atualize os dados da categoria </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nome da Categoria</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Digite o nome da categoria"
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
                                {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                            </Button>
                            <Button variant="outline" type="button" as-child>
                                <Link :href="route('categories.show', category.id)"> Cancelar </Link>
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
