<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Edit, Eye, Plus, Trash2 } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    categories: {
        data: Category[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Categorias',
        href: '/categories',
    },
];

const deleteCategory = (category: Category) => {
    if (confirm(`Tem certeza que deseja excluir a categoria "${category.name}"?`)) {
        router.delete(`/categories/${category.id}`);
    }
};
</script>

<template>
    <Head title="Categorias" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Categorias</h1>
                    <p class="text-muted-foreground">Gerencie suas categorias</p>
                </div>
                <Button as-child>
                    <Link :href="route('categories.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Nova Categoria
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Lista de Categorias</CardTitle>
                    <CardDescription> Total de {{ categories.total }} categorias encontradas </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="categories.data.length === 0" class="py-8 text-center">
                        <p class="text-muted-foreground">Nenhuma categoria encontrada.</p>
                        <Button as-child class="mt-4">
                            <Link :href="route('categories.create')">
                                <Plus class="mr-2 h-4 w-4" />
                                Criar primeira categoria
                            </Link>
                        </Button>
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="category in categories.data"
                            :key="category.id"
                            class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-muted/50"
                        >
                            <div>
                                <h3 class="font-medium">{{ category.name }}</h3>
                                <p class="text-sm text-muted-foreground">Criado em {{ new Date(category.created_at).toLocaleDateString('pt-BR') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="route('categories.show', category.id)">
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="route('categories.edit', category.id)">
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="sm" @click="deleteCategory(category)" class="text-destructive hover:text-destructive">
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="categories.last_page > 1" class="mt-6 flex items-center justify-center gap-2">
                            <Link
                                v-for="link in categories.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                :class="[
                                    'rounded-md px-3 py-2 text-sm transition-colors',
                                    link.active ? 'bg-primary text-primary-foreground' : 'hover:bg-muted',
                                    !link.url ? 'pointer-events-none opacity-50' : '',
                                ]"
                            >
                                {{ link.label.replace('&laquo;', '«').replace('&raquo;', '»') }}
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
