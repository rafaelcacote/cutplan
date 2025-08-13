<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Save, Trash2, UserPlus } from 'lucide-vue-next';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import { route } from 'ziggy-js';
const toast = useToast();
const page = usePage();

const showToastMessages = () => {
    const successMsg = (page.props.flash as any)?.success;
    if (successMsg) {
        toast.add({ severity: 'success', summary: 'Sucesso', detail: successMsg, life: 3500, group: 'main' });
    }
    const errorMsg = (page.props.flash as any)?.error;
    if (errorMsg) {
        toast.add({ severity: 'error', summary: 'Erro', detail: errorMsg, life: 3500, group: 'main' });
    }
};

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { onMounted, watch } from 'vue';
onMounted(() => showToastMessages());
watch(
    () => page.props.flash,
    () => showToastMessages(),
    { deep: true },
);

interface User {
    id: number;
    name: string;
}
interface Membro {
    id: number;
    user: User;
    papel?: string | null;
}
interface Equipe {
    id: number;
    nome: string;
    tipo: string;
    lider_user_id?: number | null;
    lider?: User | null;
    membros: Membro[];
}

const props = defineProps<{ equipe: Equipe; users: User[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Equipes', href: '/equipes' },
    { title: props.equipe.nome, href: `/equipes/${props.equipe.id}` },
];

// Form de cabeçalho (editar dados básicos)
const formEquipe = useForm({
    nome: props.equipe.nome,
    tipo: props.equipe.tipo,
    lider_user_id: props.equipe.lider_user_id ?? null,
});

const salvarEquipe = () => {
    formEquipe.put(route('equipes.update', props.equipe.id));
};

// Form adicionar membro
const formMembro = useForm<{ user_id: number | null; papel: string | null }>({ user_id: null, papel: null });
const adicionarMembro = () => {
    if (!formMembro.user_id) return;
    formMembro.post(route('equipes.membros.store', props.equipe.id), {
        onSuccess: () => {
            formMembro.reset();
            toast.add({ severity: 'success', summary: 'Membro adicionado', detail: 'Membro adicionado à equipe!', life: 3000, group: 'main' });
        },
    });
};

const atualizarPapel = (membro: Membro) => {
    router.patch(route('equipes.membros.update', { equipe: props.equipe.id, membro: membro.id }), { papel: membro.papel ?? null });
};

const showDeleteDialog = ref(false);
const membroToDelete = ref<Membro | null>(null);
const askDeleteMembro = (membro: Membro) => {
    membroToDelete.value = membro;
    showDeleteDialog.value = true;
};
const removerMembro = () => {
    if (membroToDelete.value) {
        router.delete(route('equipes.membros.destroy', { equipe: props.equipe.id, membro: membroToDelete.value.id }), {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Membro removido', detail: 'Membro removido da equipe!', life: 3000, group: 'main' });
                showDeleteDialog.value = false;
                membroToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head :title="`Equipe - ${props.equipe.nome}`" />
    <Toast position="top-right" group="main" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 md:p-8 dark:bg-[#1E293B]">
            <!-- Header voltar -->
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="route('equipes.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">{{ props.equipe.nome }}</h1>
            </div>

            <!-- Card dados básicos -->
            <Card class="p-6">
                <CardHeader>
                    <CardTitle>Dados da Equipe</CardTitle>
                    <CardDescription>Edite as informações básicas</CardDescription>
                </CardHeader>
                <CardContent>
                    <form class="grid grid-cols-1 gap-6 md:grid-cols-3" @submit.prevent="salvarEquipe">
                        <div class="space-y-2">
                            <label for="nome">Nome</label>
                            <InputText id="nome" v-model="formEquipe.nome" class="w-full" />
                        </div>
                        <div class="space-y-2">
                            <label for="tipo">Tipo</label>
                            <InputText id="tipo" v-model="formEquipe.tipo" class="w-full" />
                        </div>
                        <div class="space-y-2">
                            <label for="lider">Líder</label>
                            <Dropdown
                                inputId="lider"
                                v-model="formEquipe.lider_user_id"
                                :options="props.users"
                                optionLabel="name"
                                optionValue="id"
                                showClear
                                placeholder="Selecione o líder"
                                class="w-full"
                            />
                        </div>
                        <div class="flex justify-end md:col-span-3">
                            <Button type="submit" :disabled="formEquipe.processing" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white">
                                <Save class="mr-2 h-4 w-4" />
                                {{ formEquipe.processing ? 'Salvando...' : 'Salvar alterações' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>

            <!-- Card membros -->
            <Card class="p-6">
                <CardHeader>
                    <CardTitle>Membros</CardTitle>
                    <CardDescription>Gerencie os membros da equipe</CardDescription>
                </CardHeader>
                <CardContent>
                    <!-- Adicionar membro -->
                    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <Dropdown
                            v-model="formMembro.user_id"
                            :options="props.users"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Selecionar usuário"
                            class="w-full"
                        />
                        <InputText v-model="formMembro.papel" placeholder="Papel (opcional)" class="w-full" />
                        <Button
                            @click="adicionarMembro"
                            :disabled="formMembro.processing || !formMembro.user_id"
                            class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white"
                        >
                            <UserPlus class="mr-2 h-4 w-4" /> Adicionar
                        </Button>
                    </div>
                    <!-- Lista de membros -->
                    <DataTable :value="props.equipe.membros" class="w-full" responsiveLayout="scroll" stripedRows rowHover>
                        <Column header="Usuário">
                            <template #body="{ data }">
                                <span class="font-medium">{{ (data as any).user.name }}</span>
                            </template>
                        </Column>
                        <Column header="Papel">
                            <template #body="{ data }">
                                <InputText v-model="(data as any).papel" @blur="atualizarPapel(data as any)" placeholder="Definir papel" />
                            </template>
                        </Column>
                        <Column header="Ações" style="width: 120px">
                            <template #body="{ data }">
                                <Button variant="ghost" class="text-red-500" @click="askDeleteMembro(data as any)">
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </template>
                        </Column>
                    </DataTable>
                </CardContent>
            </Card>

            <!-- Dialog de confirmação de exclusão de membro -->
            <Dialog v-model:visible="showDeleteDialog" :modal="true" :closable="false" :style="{ width: '100%', maxWidth: '28rem' }">
                <div
                    class="animate-fade-in space-y-8 rounded-2xl border border-red-200 bg-white p-8 shadow-2xl transition-all duration-300 dark:border-red-800 dark:bg-[#1E293B]"
                >
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 flex-shrink-0 animate-pulse items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                            <Trash2 class="h-7 w-7 text-red-500" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-500">Remover Membro</h3>
                            <p class="text-sm text-[#64748B] dark:text-[#CBD5E1]">
                                Esta ação <span class="font-semibold text-red-500">não pode ser desfeita</span>.
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-lg text-[#1E293B] dark:text-white">
                            Tem certeza que deseja remover
                            <span class="font-semibold text-[#3B82F6] dark:text-[#60A5FA]">{{ membroToDelete?.user.name }}</span>
                            da equipe?
                        </p>
                        <div class="flex items-center gap-3 rounded-md border-l-4 border-red-500 bg-red-50 p-4 dark:bg-red-900">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"
                                />
                            </svg>
                            <span class="text-sm font-medium text-red-500"
                                >Todos os dados relacionados a este membro serão <u>permanentemente removidos</u>.</span
                            >
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 border-t border-[#E0E7EF] pt-6 dark:border-[#334155]">
                        <Button
                            variant="outline"
                            @click="showDeleteDialog = false"
                            class="rounded-lg px-7 py-2 text-base transition-all hover:bg-[#F1F5F9] dark:hover:bg-[#334155]"
                        >
                            Cancelar
                        </Button>
                        <Button
                            variant="destructive"
                            @click="removerMembro"
                            class="flex items-center gap-2 rounded-lg px-7 py-2 text-base shadow transition-all hover:bg-red-600 hover:text-white"
                        >
                            <Trash2 class="h-5 w-5" />
                            <span>Remover</span>
                        </Button>
                    </div>
                </div>
            </Dialog>
        </div>
    </AppLayout>
</template>
