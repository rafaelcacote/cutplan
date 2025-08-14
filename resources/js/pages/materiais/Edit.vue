<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Checkbox from 'primevue/checkbox';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save, Package } from 'lucide-vue-next';

interface TipoMaterial {
    id: number;
    nome: string;
}

interface Unidade {
    id: number;
    codigo: string;
    nome: string;
}

interface Fornecedor {
    id: number;
    nome: string;
}

interface Material {
    id: number;
    sku: string;
    nome: string;
    tipo_id: number;
    unidade_id: number;
    fornecedor_padrao_id?: number;
    preco_custo?: number;
    estoque_minimo?: number;
    controla_estoque: boolean;
    ativo: boolean;
}

interface Props {
    material: Material;
    tipos: TipoMaterial[];
    unidades: Unidade[];
    fornecedores: Fornecedor[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Materiais',
        href: '/materiais',
    },
    {
        title: `Editar ${props.material.nome}`,
        href: `/materiais/${props.material.id}/edit`,
    },
];

const form = useForm({
    sku: props.material.sku,
    nome: props.material.nome,
    tipo_id: props.material.tipo_id,
    unidade_id: props.material.unidade_id,
    fornecedor_padrao_id: props.material.fornecedor_padrao_id,
    preco_custo: props.material.preco_custo,
    estoque_minimo: props.material.estoque_minimo,
    controla_estoque: props.material.controla_estoque,
    ativo: props.material.ativo,
});

const submit = () => {
    form.put(route('materiais.update', props.material.id), {
        onSuccess: () => {
            // O redirect será feito pelo backend
        }
    });
};

const tipoOptions = props.tipos.map(tipo => ({
    label: tipo.nome,
    value: tipo.id
}));

const unidadeOptions = props.unidades.map(unidade => ({
    label: `${unidade.codigo} - ${unidade.nome}`,
    value: unidade.id
}));

const fornecedorOptions = [
    { label: 'Nenhum fornecedor padrão', value: null },
    ...props.fornecedores.map(fornecedor => ({
        label: fornecedor.nome,
        value: fornecedor.id
    }))
];
</script>

<template>
    <Head :title="`Editar ${material.nome}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col min-h-[calc(100vh-64px)] w-full bg-background p-0">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 px-8 pt-8 pb-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <Link :href="route('materiais.index')">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA] flex items-center gap-2">
                            <Package class="h-8 w-8" />
                            Editar Material
                        </h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Altere os dados do material</p>
                    </div>
                </div>
            </div>
            <form @submit.prevent="submit" class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>Editar Material: {{ material.nome }}</CardTitle>
                        <CardDescription>Altere os dados do material abaixo</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Coluna Esquerda -->
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="sku">SKU *</Label>
                                    <InputText 
                                        id="sku" 
                                        v-model="form.sku" 
                                        placeholder="Digite o SKU do material" 
                                        class="w-full" 
                                        :class="{ 'p-invalid': form.errors.sku }"
                                    />
                                    <p v-if="form.errors.sku" class="text-sm text-destructive">{{ form.errors.sku }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="nome">Nome *</Label>
                                    <InputText 
                                        id="nome" 
                                        v-model="form.nome" 
                                        placeholder="Digite o nome do material" 
                                        class="w-full" 
                                        :class="{ 'p-invalid': form.errors.nome }"
                                    />
                                    <p v-if="form.errors.nome" class="text-sm text-destructive">{{ form.errors.nome }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="tipo_id">Tipo de Material *</Label>
                                    <Dropdown
                                        v-model="form.tipo_id"
                                        :options="tipoOptions"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Selecione o tipo"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.tipo_id }"
                                    />
                                    <p v-if="form.errors.tipo_id" class="text-sm text-destructive">{{ form.errors.tipo_id }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="unidade_id">Unidade *</Label>
                                    <Dropdown
                                        v-model="form.unidade_id"
                                        :options="unidadeOptions"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Selecione a unidade"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.unidade_id }"
                                    />
                                    <p v-if="form.errors.unidade_id" class="text-sm text-destructive">{{ form.errors.unidade_id }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="fornecedor_padrao_id">Fornecedor Padrão</Label>
                                    <Dropdown
                                        v-model="form.fornecedor_padrao_id"
                                        :options="fornecedorOptions"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Selecione o fornecedor"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.fornecedor_padrao_id }"
                                    />
                                    <p v-if="form.errors.fornecedor_padrao_id" class="text-sm text-destructive">{{ form.errors.fornecedor_padrao_id }}</p>
                                </div>
                            </div>

                            <!-- Coluna Direita -->
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="preco_custo">Preço de Custo</Label>
                                    <InputNumber
                                        v-model="form.preco_custo"
                                        inputId="preco_custo"
                                        mode="currency"
                                        currency="BRL"
                                        locale="pt-BR"
                                        placeholder="R$ 0,00"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.preco_custo }"
                                        :minFractionDigits="2"
                                        :maxFractionDigits="4"
                                    />
                                    <p v-if="form.errors.preco_custo" class="text-sm text-destructive">{{ form.errors.preco_custo }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="estoque_minimo">Estoque Mínimo</Label>
                                    <InputNumber
                                        v-model="form.estoque_minimo"
                                        inputId="estoque_minimo"
                                        placeholder="0"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.estoque_minimo }"
                                        :minFractionDigits="0"
                                        :maxFractionDigits="4"
                                        :min="0"
                                    />
                                    <p v-if="form.errors.estoque_minimo" class="text-sm text-destructive">{{ form.errors.estoque_minimo }}</p>
                                </div>

                                <!-- Checkboxes -->
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox 
                                            v-model="form.controla_estoque" 
                                            inputId="controla_estoque" 
                                            :binary="true"
                                            :class="{ 'p-invalid': form.errors.controla_estoque }"
                                        />
                                        <Label for="controla_estoque">Controla Estoque</Label>
                                    </div>
                                    <p v-if="form.errors.controla_estoque" class="text-sm text-destructive">{{ form.errors.controla_estoque }}</p>

                                    <div class="flex items-center space-x-2">
                                        <Checkbox 
                                            v-model="form.ativo" 
                                            inputId="ativo" 
                                            :binary="true"
                                            :class="{ 'p-invalid': form.errors.ativo }"
                                        />
                                        <Label for="ativo">Material Ativo</Label>
                                    </div>
                                    <p v-if="form.errors.ativo" class="text-sm text-destructive">{{ form.errors.ativo }}</p>
                                </div>

                                <!-- Informações adicionais -->
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Informações</h4>
                                    <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1">
                                        <li>• O SKU deve ser único para cada material</li>
                                        <li>• Campos marcados com * são obrigatórios</li>
                                        <li>• O controle de estoque gerencia automaticamente as quantidades</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 justify-end mt-8">
                            <Button type="submit" :disabled="form.processing" size="lg" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-8 py-3 text-lg font-semibold transition-transform duration-300 hover:scale-105">
                                <Save class="mr-2 h-5 w-5" />
                                {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                            </Button>
                            <Button variant="outline" type="button" as-child size="lg" class="px-8 py-3 text-lg border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
                                <Link :href="route('materiais.index')"> Cancelar </Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped>
.p-invalid {
    border-color: #ef4444 !important;
}
</style>
