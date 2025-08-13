<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';

interface User {
    id: number;
    name: string;
}
const props = defineProps<{ users: User[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Equipes', href: '/equipes' },
    { title: 'Nova Equipe', href: '/equipes/create' },
];

const form = useForm({
    nome: '',
    tipo: '',
    lider_user_id: null as number | null,
});

const submit = () => {
    form.post(route('equipes.store'));
};
</script>

<template>
    <Head title="Nova Equipe" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-[calc(100vh-64px)] w-full flex-col bg-background p-0">
            <div class="flex flex-col items-start justify-between gap-4 px-8 pt-8 pb-4 md:flex-row md:items-center">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('equipes.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Nova Equipe</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Cadastre uma nova equipe</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Nova Equipe</CardTitle>
                        <CardDescription>Preencha os dados da equipe abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="nome">Nome</Label>
                                    <InputText id="nome" v-model="form.nome" placeholder="Ex.: Equipe de Montagem" class="w-full" />
                                    <p v-if="form.errors.nome" class="text-sm text-destructive">{{ form.errors.nome }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="tipo">Tipo</Label>
                                    <InputText id="tipo" v-model="form.tipo" placeholder="Ex.: Montagem, Comercial, Suporte" class="w-full" />
                                    <p v-if="form.errors.tipo" class="text-sm text-destructive">{{ form.errors.tipo }}</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="lider">Líder (opcional)</Label>
                                    <Dropdown
                                        inputId="lider"
                                        v-model="form.lider_user_id"
                                        :options="props.users"
                                        optionLabel="name"
                                        optionValue="id"
                                        showClear
                                        placeholder="Selecione o líder"
                                        class="w-full"
                                    />
                                    <p v-if="form.errors.lider_user_id" class="text-sm text-destructive">{{ form.errors.lider_user_id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                size="lg"
                                class="border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-8 py-3 text-lg font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                            >
                                <Save class="mr-2 h-5 w-5" />
                                {{ form.processing ? 'Salvando...' : 'Salvar' }}
                            </Button>
                            <Button
                                variant="outline"
                                type="button"
                                as-child
                                size="lg"
                                class="rounded-lg border-[#3B82F6] px-8 py-3 text-lg text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                <Link :href="route('equipes.index')"> Cancelar </Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
