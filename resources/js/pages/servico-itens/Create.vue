<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';
import Checkbox from 'primevue/checkbox';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { computed } from 'vue';

interface ServicoOption {
    value: number;
    label: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Itens de Serviço',
        href: '/servico-itens',
    },
    {
        title: 'Novo Item',
        href: '/servico-itens/create',
    },
];

const form = useForm({
    descricao_item: '',
    ordem: null as number | null,
    opcional: false,
});

const toast = useToast();

const handleSubmit = () => {
    if (!form.descricao_item.trim()) {
        toast.add({
            severity: 'error',
            summary: 'Erro de Validação',
            detail: 'Por favor, informe a descrição do item.',
            life: 4000,
            group: 'main',
        });
        return;
    }

    form.post('/servico-itens', {
        onSuccess: () => {},
        onError: (errors) => {
            Object.values(errors).forEach((error: any) => {
                toast.add({
                    severity: 'error',
                    summary: 'Erro de Validação',
                    detail: error,
                    life: 5000,
                    group: 'main',
                });
            });
        },
    });
};

const isFormValid = computed(() => {
    return form.descricao_item.trim();
});
</script>

<template>
    <Head title="Novo Item de Serviço" />
    <Toast position="top-right" group="main" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <!-- Header -->
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Novo Item de Serviço</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Crie um novo item para um serviço</p>
                </div>
                <Button
                    variant="outline"
                    as-child
                    class="rounded-lg border-[#3B82F6] text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                >
                    <Link :href="route('servico-itens.index')">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Voltar
                    </Link>
                </Button>
            </div>

            <!-- Formulário -->
            <Card
                class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl transition-all duration-300 dark:border-[#334155] dark:bg-[#1E293B]"
            >
                <CardHeader>
                    <CardTitle class="text-[#1E293B] dark:text-white">Informações do Item</CardTitle>
                    <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]"> Preencha as informações do novo item de serviço </CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <form @submit.prevent="handleSubmit" class="space-y-6">
                        <!-- Serviço removido -->

                        <!-- Descrição do Item -->
                        <div class="space-y-2">
                            <Label for="descricao_item" class="text-sm font-medium text-[#1E293B] dark:text-white">
                                Descrição do Item <span class="text-red-500">*</span>
                            </Label>
                            <Textarea
                                id="descricao_item"
                                v-model="form.descricao_item"
                                placeholder="Descreva detalhadamente este item do serviço..."
                                class="min-h-[180px] w-full resize-y p-4 text-base"
                                :class="{ 'border-red-500': form.errors.descricao_item }"
                            />
                            <div v-if="form.errors.descricao_item" class="text-sm text-red-500">{{ form.errors.descricao_item }}</div>
                        </div>

                        <!-- Ordem e Opcional -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Ordem -->
                            <div class="space-y-2">
                                <Label for="ordem" class="text-sm font-medium text-[#1E293B] dark:text-white"> Ordem de Execução </Label>
                                <InputNumber
                                    id="ordem"
                                    v-model="form.ordem"
                                    placeholder="Ex: 1, 2, 3..."
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.ordem }"
                                    :min="1"
                                    :useGrouping="false"
                                />
                                <p class="text-xs text-[#64748B] dark:text-[#CBD5E1]">Define a ordem de execução deste item no serviço</p>
                                <div v-if="form.errors.ordem" class="text-sm text-red-500">{{ form.errors.ordem }}</div>
                            </div>

                            <!-- Opcional -->
                            <div class="space-y-2">
                                <Label for="opcional" class="text-sm font-medium text-[#1E293B] dark:text-white"> Tipo do Item </Label>
                                <div class="flex items-center space-x-3 rounded-lg border bg-[#F8FAFC] p-4 dark:bg-[#334155]">
                                    <Checkbox id="opcional" v-model="form.opcional" :binary="true" class="shrink-0" />
                                    <div class="flex-1">
                                        <label for="opcional" class="cursor-pointer text-sm font-medium text-[#1E293B] dark:text-white">
                                            {{ form.opcional ? 'Item Opcional' : 'Item Obrigatório' }}
                                        </label>
                                        <p class="mt-1 text-xs text-[#64748B] dark:text-[#CBD5E1]">
                                            {{
                                                form.opcional
                                                    ? 'Este item pode ser pulado durante a execução do serviço'
                                                    : 'Este item é obrigatório para a execução do serviço'
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="form.errors.opcional" class="text-sm text-red-500">{{ form.errors.opcional }}</div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex justify-end gap-4 border-t border-[#E0E7EF] pt-6 dark:border-[#334155]">
                            <Button
                                type="button"
                                variant="outline"
                                as-child
                                class="rounded-lg border-[#3B82F6] px-6 py-2 text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                            >
                                <Link :href="route('servico-itens.index')"> Cancelar </Link>
                            </Button>
                            <Button
                                type="submit"
                                :disabled="!isFormValid || form.processing"
                                class="rounded-lg border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 py-2 font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:scale-100"
                            >
                                <Save class="mr-2 h-4 w-4" />
                                {{ form.processing ? 'Salvando...' : 'Salvar Item' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
.p-autocomplete,
.p-inputnumber,
.p-checkbox {
    border-radius: 0.75rem !important;
}

.p-autocomplete-panel {
    border-radius: 0.75rem !important;
    box-shadow:
        0 10px 25px -5px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

.p-autocomplete-items .p-autocomplete-item {
    padding: 0.75rem 1rem !important;
    transition: all 0.2s !important;
}

.p-autocomplete-items .p-autocomplete-item:hover {
    background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%) !important;
    color: #fff !important;
}

.p-checkbox .p-checkbox-box {
    border-radius: 0.375rem !important;
    border: 2px solid #e0e7ef !important;
    transition: all 0.3s !important;
}

.p-checkbox .p-checkbox-box.p-highlight {
    background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%) !important;
    border-color: transparent !important;
}

.p-inputnumber-input {
    border-radius: 0.75rem !important;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
