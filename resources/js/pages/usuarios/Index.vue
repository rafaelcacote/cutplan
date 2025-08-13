<template>
    <Head title="Usuários" />
    <Toast position="top-right" group="main" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Usuários</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Gerencie usuários e suas permissões</p>
                </div>
                <Button
                    as-child
                    class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                >
                    <Link :href="route('usuarios.create')">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Usuário
                    </Link>
                </Button>
            </div>

            <!-- Tabela -->
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Lista de Usuários</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]"> Mostrando {{ users.length }} usuários </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="relative">
                        <DataTable
                            :value="users"
                            class="p-datatable-sm striped highlight-on-hover premium-table w-full"
                            responsiveLayout="scroll"
                            stripedRows
                            rowHover
                        >
                            <Column field="name" header="Nome" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-[#1E293B] dark:text-white">{{ data.name }}</div>
                                </template>
                            </Column>
                            <Column field="email" header="E-mail" sortable>
                                <template #body="{ data }">
                                    <div class="text-sm">
                                        <a :href="`mailto:${data.email}`" class="text-[#3B82F6] hover:underline dark:text-[#60A5FA]">{{
                                            data.email
                                        }}</a>
                                    </div>
                                </template>
                            </Column>
                            <Column header="Perfis">
                                <template #body="{ data }">
                                    <div class="flex flex-wrap gap-1">
                                        <Tag v-for="role in data.roles" :key="role" :value="role" severity="info" class="text-xs" />
                                    </div>
                                </template>
                            </Column>
                            <Column header="Ações" class="w-32">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Button
                                            @click="goToEdit(data)"
                                            size="sm"
                                            variant="ghost"
                                            class="text-[#3B82F6] hover:bg-[#3B82F6]/10 dark:text-[#60A5FA]"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                        <Button @click="askDeleteUser(data)" size="sm" variant="ghost" class="text-red-500 hover:bg-red-50">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </CardContent>
            </Card>

            <!-- Modal de confirmação de exclusão -->
            <Dialog v-model:visible="showDeleteDialog" modal :closable="false" class="mx-auto w-full max-w-md">
                <template #container>
                    <div
                        class="space-y-8 rounded-2xl border border-red-200 bg-white p-8 shadow-2xl transition-all duration-300 dark:border-red-800 dark:bg-[#1E293B]"
                    >
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                                <Trash2 class="h-7 w-7 text-red-500" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-red-500">Excluir Usuário</h3>
                                <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">Esta ação não pode ser desfeita.</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-lg text-[#1E293B] dark:text-white">
                                Tem certeza que deseja excluir o usuário
                                <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ userToDelete?.name }}</span
                                >?
                            </p>
                        </div>
                        <div class="flex justify-end gap-4 border-t border-[#E0E7EF] pt-6 dark:border-[#334155]">
                            <Button variant="outline" @click="showDeleteDialog = false" class="px-7 py-2 text-base">Cancelar</Button>
                            <Button variant="destructive" @click="confirmDeleteUser" class="flex items-center gap-2 px-7 py-2 text-base">
                                <Trash2 class="h-5 w-5" />
                                Excluir
                            </Button>
                        </div>
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import { onMounted, ref } from 'vue';
import api from '../../api';

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Usuários', href: '' },
];

const users = ref([]);
const showDeleteDialog = ref(false);
const userToDelete = ref<any | null>(null);

const fetchUsers = async () => {
    const response = await api.get('/api/usuarios');
    users.value = response.data;
};

const goToEdit = (user: any) => {
    router.visit(`/usuarios/${user.id}/edit`);
};

const askDeleteUser = (user: any) => {
    userToDelete.value = user;
    showDeleteDialog.value = true;
};

const confirmDeleteUser = async () => {
    if (!userToDelete.value) return;
    await api.delete(`/api/usuarios/${userToDelete.value.id}`);
    showDeleteDialog.value = false;
    userToDelete.value = null;
    fetchUsers();
};

onMounted(fetchUsers);
</script>

<style scoped>
.premium-table {
    --p-datatable-header-cell-background: #f8fafc;
    --p-datatable-row-background: #ffffff;
    --p-datatable-row-hover-background: #f1f5f9;
}
</style>
