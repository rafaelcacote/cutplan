<template>
    <div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Primeira linha: SKU, Nome, Tipo -->
            <div class="space-y-2">
                <label for="sku" class="font-semibold">SKU</label>
                <InputText id="sku" v-model="form.sku" required maxlength="64" class="w-full" />
                <p v-if="form.errors?.sku" class="text-sm text-destructive">{{ form.errors.sku }}</p>
            </div>
            <div class="space-y-2">
                <label for="nome" class="font-semibold">Nome</label>
                <InputText id="nome" v-model="form.nome" required maxlength="150" class="w-full" />
                <p v-if="form.errors?.nome" class="text-sm text-destructive">{{ form.errors.nome }}</p>
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <label for="tipo_id" class="font-semibold">Tipo</label>
                    <Button
                        type="button"
                        size="icon"
                        class="flex !h-7 !w-7 items-center justify-center border border-blue-400 bg-white !p-0 shadow-sm transition-colors duration-150 hover:bg-blue-50"
                        style="border-radius: 50%"
                        @click="showTipoDialog = true"
                        aria-label="Adicionar novo tipo"
                    >
                        <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <p v-if="form.errors?.tipo_id" class="text-sm text-destructive">{{ form.errors.tipo_id }}</p>
                <DialogNovoTipo v-model="showTipoDialog" @salvar="adicionarNovoTipo" @cancel="showTipoDialog = false" />
            </div>

            <!-- Segunda linha: Unidade, Fornecedor, Preço de Custo -->
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <label for="unidade_id" class="font-semibold">Unidade</label>
                    <Button
                        type="button"
                        size="icon"
                        class="flex !h-7 !w-7 items-center justify-center border border-blue-400 bg-white !p-0 shadow-sm transition-colors duration-150 hover:bg-blue-50"
                        style="border-radius: 50%"
                        @click="showUnidadeDialog = true"
                        aria-label="Adicionar nova unidade"
                    >
                        <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <p v-if="form.errors?.unidade_id" class="text-sm text-destructive">{{ form.errors.unidade_id }}</p>
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
                <p v-if="form.errors?.fornecedor_padrao_id" class="text-sm text-destructive">{{ form.errors.fornecedor_padrao_id }}</p>
            </div>
            <div class="space-y-2">
                <label for="preco_custo" class="font-semibold">Preço de Custo</label>
                <InputText id="preco_custo" v-model="form.preco_custo" type="number" step="0.01" class="w-full" />
                <p v-if="form.errors?.preco_custo" class="text-sm text-destructive">{{ form.errors.preco_custo }}</p>
            </div>

            <!-- Terceira linha: Estoque Mínimo, Controla Estoque, Ativo -->
            <div class="space-y-2">
                <label for="estoque_minimo" class="font-semibold">Estoque Mínimo</label>
                <InputText id="estoque_minimo" v-model="form.estoque_minimo" type="number" step="0.01" class="w-full" />
                <p v-if="form.errors?.estoque_minimo" class="text-sm text-destructive">{{ form.errors.estoque_minimo }}</p>
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
                <p v-if="form.errors?.controla_estoque" class="text-sm text-destructive">{{ form.errors.controla_estoque }}</p>
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
                <p v-if="form.errors?.ativo" class="text-sm text-destructive">{{ form.errors.ativo }}</p>
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
                <Link :href="route('materiais.index')">Cancelar</Link>
            </Button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import { Save } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DialogNovaUnidade from './components/DialogNovaUnidade.vue';
import DialogNovoTipo from './components/DialogNovoTipo.vue';

interface Props {
    form: any;
    unidades: Array<any>;
    fornecedores: Array<any>;
    tiposmaterial: Array<any>;
}

const props = defineProps<Props>();

function unidadeOptionLabel(unidade: any) {
    if (!unidade) return '';
    if (unidade.codigo) {
        return `${unidade.nome} (${unidade.codigo})`;
    }
    return unidade.nome || '';
}

const showTipoDialog = ref(false);
const showUnidadeDialog = ref(false);
const tiposMaterialLocal = ref<Array<{ id: number | string; nome: string }>>([]);
const unidadesLocal = ref<Array<{ id: number | string; nome: string; codigo: string }>>([]);

// Inicializa lista local ao montar ou quando prop muda
watch(
    () => props.tiposmaterial,
    (val) => {
        tiposMaterialLocal.value = Array.isArray(val) ? [...val] : [];
    },
    { immediate: true, deep: true },
);
watch(
    () => props.unidades,
    (val) => {
        unidadesLocal.value = Array.isArray(val) ? [...val] : [];
    },
    { immediate: true, deep: true },
);

function adicionarNovaUnidade(unidade: { id: number | string; nome: string; codigo: string }) {
    if (!unidade || !unidade.nome || !unidade.codigo) return;
    unidadesLocal.value.push(unidade);
    props.form.unidade_id = unidade.id;
    showUnidadeDialog.value = false;
}

function adicionarNovoTipo(nome: string) {
    if (!nome.trim()) return;
    const novoTipo = { id: Date.now() * -1, nome };
    tiposMaterialLocal.value.push(novoTipo);
    props.form.tipo_id = novoTipo.id;
    showTipoDialog.value = false;
}
</script>
