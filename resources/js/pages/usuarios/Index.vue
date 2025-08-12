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
                    @click="showCreate = true"
                    class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 text-base font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                >
                    <Plus class="mr-2 h-4 w-4" />
                    Novo Usuário
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
                                            @click="editUser(data)"
                                            size="sm"
                                            variant="ghost"
                                            class="text-[#3B82F6] hover:bg-[#3B82F6]/10 dark:text-[#60A5FA]"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                        <Button @click="deleteUser(data)" size="sm" variant="ghost" class="text-red-500 hover:bg-red-50">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </CardContent>
            </Card>

            <!-- Dialog para criar usuário -->
            <Dialog v-model:open="showCreate" class="max-w-2xl">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Novo Usuário</DialogTitle>
                        <DialogDescription> Preencha os dados para criar um novo usuário </DialogDescription>
                    </DialogHeader>
                    <UserForm @saved="onUserSaved" />
                </DialogContent>
            </Dialog>

            <!-- Dialog para editar usuário -->
            <Dialog v-model:open="showEdit" class="max-w-2xl">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Editar Usuário</DialogTitle>
                        <DialogDescription> Atualize os dados do usuário </DialogDescription>
                    </DialogHeader>
                    <UserForm :user="selectedUser" @saved="onUserSaved" />
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import { onMounted, ref } from 'vue';
import api from '../../api';
import UserForm from './UserForm.vue';

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Usuários', href: '' },
];

const users = ref([]);
const showCreate = ref(false);
const showEdit = ref(false);
const selectedUser = ref(null);

const fetchUsers = async () => {
    const response = await api.get('/usuarios');
    users.value = response.data;
};

const editUser = (user: any) => {
    selectedUser.value = user;
    showEdit.value = true;
};

const deleteUser = (user: any) => {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        router.delete(`/usuarios/${user.id}`, {
            onSuccess: fetchUsers,
        });
    }
};

const onUserSaved = () => {
    showCreate.value = false;
    showEdit.value = false;
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
