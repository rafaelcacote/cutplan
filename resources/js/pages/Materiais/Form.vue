<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Primeira linha: SKU, Nome, Tipo -->
            <div class="space-y-2">
                <label for="sku" class="font-semibold">SKU</label>
                <InputText id="sku" v-model="form.sku" required maxlength="64" class="w-full" />
            </div>
            <div class="space-y-2">
                <label for="nome" class="font-semibold">Nome</label>
                <InputText id="nome" v-model="form.nome" required maxlength="150" class="w-full" />
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <label for="tipo_id" class="font-semibold">Tipo</label>
                    <Button
                        type="button"
                        size="icon"
                        class="!h-7 !w-7 !p-0 border border-blue-400 bg-white hover:bg-blue-50 transition-colors duration-150 flex items-center justify-center shadow-sm"
                        style="border-radius: 50%"
                        @click="showTipoDialog = true"
                        aria-label="Adicionar novo tipo"
                    >
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </Button>
                </div>
                <Dropdown
                    id="tipo_id"
                    v-model="form.tipo_id"
                    :options="tiposMaterialLocal"
                    optionLabel="nome"
                    optionValue="id"
                    placeholder="Selecione o tipo"
                    class="w-full"
                />
                <DialogNovoTipo v-model="showTipoDialog" @salvar="adicionarNovoTipo" @cancel="showTipoDialog = false" />
            </div>

            <!-- Segunda linha: Unidade, Fornecedor, Preço de Custo -->
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <label for="unidade_id" class="font-semibold">Unidade</label>
                    <Button
                        type="button"
                        size="icon"
                        class="!h-7 !w-7 !p-0 border border-blue-400 bg-white hover:bg-blue-50 transition-colors duration-150 flex items-center justify-center shadow-sm"
                        style="border-radius: 50%"
                        @click="showUnidadeDialog = true"
                        aria-label="Adicionar nova unidade"
                    >
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </Button>
                </div>
                <Dropdown
                    id="unidade_id"
                    v-model="form.unidade_id"
                    :options="unidadesLocal"
                    :optionLabel="unidadeOptionLabel"
                    optionValue="id"
                    placeholder="Selecione a unidade"
                    class="w-full"
                />
                <DialogNovaUnidade v-model="showUnidadeDialog" @salvar="adicionarNovaUnidade" @cancel="showUnidadeDialog = false" />
            </div>
            <div class="space-y-2">
                <label for="fornecedor_padrao_id" class="font-semibold">Fornecedor Padrão</label>
                <Dropdown
                    id="fornecedor_padrao_id"
                    v-model="form.fornecedor_padrao_id"
                    :options="fornecedores"
                    optionLabel="nome"
                    optionValue="id"
                    placeholder="Selecione o fornecedor"
                    class="w-full"
                />
            </div>
            <div class="space-y-2">
                <label for="preco_custo" class="font-semibold">Preço de Custo</label>
                <InputText id="preco_custo" v-model="form.preco_custo" type="number" step="0.01" class="w-full" />
            </div>

            <!-- Terceira linha: Estoque Mínimo, Controla Estoque, Ativo -->
            <div class="space-y-2">
                <label for="estoque_minimo" class="font-semibold">Estoque Mínimo</label>
                <InputText id="estoque_minimo" v-model="form.estoque_minimo" type="number" step="0.01" class="w-full" />
            </div>
            <div class="space-y-2">
                <label class="font-semibold">Controla Estoque</label>
                <Dropdown
                    v-model="form.controla_estoque"
                    :options="[
                        { label: 'Sim', value: 1 },
                        { label: 'Não', value: 0 },
                    ]"
                    optionLabel="label"
                    optionValue="value"
                    class="w-full"
                />
            </div>
            <div class="space-y-2">
                <label class="font-semibold">Ativo</label>
                <Dropdown
                    v-model="form.ativo"
                    :options="[
                        { label: 'Sim', value: 1 },
                        { label: 'Não', value: 0 },
                    ]"
                    optionLabel="label"
                    optionValue="value"
                    class="w-full"
                />
            </div>
        </div>
        <div class="mt-8 flex items-center justify-end gap-4">
            <Button
                type="submit"
                :disabled="form.processing"
                size="lg"
                class="border-0 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-8 py-3 text-lg font-semibold text-white shadow-lg transition-transform duration-300 hover:scale-105"
            >
                Salvar
            </Button>
            <Button
                type="button"
                variant="outline"
                size="lg"
                class="rounded-lg border-[#3B82F6] px-8 py-3 text-lg text-[#3B82F6] transition-all duration-300 hover:bg-[#EFF6FF] dark:text-[#60A5FA] dark:hover:bg-[#334155]"
                @click="$emit('cancel')"
            >
                Cancelar
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { reactive, ref, watch } from 'vue';
import DialogNovoTipo from './components/DialogNovoTipo.vue';
import DialogNovaUnidade from './components/DialogNovaUnidade.vue';

const props = defineProps({
    modelValue: Object,
    unidades: Array,
    fornecedores: Array,
    tiposmaterial: Array,
});
const emit = defineEmits(['update:modelValue', 'submit', 'cancel']);

function unidadeOptionLabel(unidade: any) {
    if (!unidade) return '';
    if (unidade.codigo) {
        return `${unidade.nome} (${unidade.codigo})`;
    }
    return unidade.nome || '';
}

const showTipoDialog = ref(false);
const showUnidadeDialog = ref(false);
let novoTipoNome = '';
const tiposMaterialLocal = ref<Array<{ id: number | string; nome: string }>>([]);
const unidadesLocal = ref<Array<{ id: number | string; nome: string; codigo: string }>>([]);

// Inicializa lista local ao montar ou quando prop muda
watch(
    () => props.tiposmaterial,
    (val) => {
        tiposMaterialLocal.value = Array.isArray(val) ? val.map((t) => ({ ...t })) : [];
    },
    { immediate: true, deep: true },
);
watch(
    () => props.unidades,
    (val) => {
        unidadesLocal.value = Array.isArray(val) ? val.map((u) => ({ ...u })) : [];
    },
    { immediate: true, deep: true },
);
function adicionarNovaUnidade(unidade: { id: number | string; nome: string; codigo: string }) {
    if (!unidade || !unidade.nome || !unidade.codigo) return;
    unidadesLocal.value.push(unidade);
    form.unidade_id = unidade.id;
    showUnidadeDialog.value = false;
}

function adicionarNovoTipo(nome: string) {
    if (!nome.trim()) return;
    const novoTipo = { id: Date.now() * -1, nome };
    tiposMaterialLocal.value.push(novoTipo);
    form.tipo_id = novoTipo.id;
    showTipoDialog.value = false;
}

const form = reactive({
    sku: '',
    nome: '',
    tipo_id: null as number | string | null,
    unidade_id: null,
    fornecedor_padrao_id: null,
    preco_custo: '',
    estoque_minimo: '',
    controla_estoque: 1,
    ativo: 1,
    processing: false,
});

watch(
    () => props.modelValue,
    (val) => {
        if (val) Object.assign(form, JSON.parse(JSON.stringify(val)));
    },
    { immediate: true, deep: true },
);

watch(
    form,
    () => {
        emit('update:modelValue', JSON.parse(JSON.stringify(form)));
    },
    { deep: true },
);

function submit() {
    form.processing = true;
    emit('submit', { ...form });
    form.processing = false;
}
</script>
