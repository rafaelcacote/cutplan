<template>
    <Head title="Novo Fornecedor" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-[calc(100vh-64px)] w-full flex-col bg-background p-0">
            <div class="flex flex-col items-start justify-between gap-4 px-8 pt-8 pb-4 md:flex-row md:items-center">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('fornecedores.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Novo Fornecedor</h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Cadastre um novo fornecedor</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="handleSubmit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Novo Fornecedor</CardTitle>
                        <CardDescription>Preencha os dados do fornecedor abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <FornecedorForm :enderecos="enderecos" v-model="formData" />
                        <div class="mt-8 flex items-center justify-end gap-4">
                            <Button
                                type="submit"
                                :disabled="processing"
                                size="lg"
                                class="border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-8 py-3 text-lg font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
                            >
                                <Save class="mr-2 h-5 w-5" />
                                {{ processing ? 'Salvando...' : 'Salvar' }}
                            </Button>
                            <Button
                                variant="outline"
                                type="button"
                                as-child
                                size="lg"
                                class="rounded-lg border-[#3B82F6] px-8 py-3 text-lg text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                <Link :href="route('fornecedores.index')"> Cancelar </Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import FornecedorForm from './Form.vue';

const props = defineProps({ enderecos: Array });

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Fornecedores', href: '/fornecedores' },
    { title: 'Novo Fornecedor', href: '/fornecedores/create' },
];

const toast = useToast();
const formData = ref({});
const processing = ref(false);

function handleSubmit() {
    processing.value = true;
    router.post('/fornecedores', formData.value, {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Sucesso',
                detail: 'Fornecedor registrado com sucesso!',
                life: 4000,
                group: 'main',
            });
        },
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
