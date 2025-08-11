<template>
    <Dialog
        :visible="modelValue"
        @update:visible="(val) => $emit('update:modelValue', val)"
        modal
        header="Nova Unidade"
        :style="{ width: '400px', borderRadius: '16px', padding: '0' }"
        contentClass="p-0"
    >
        <div class="flex flex-col gap-4 p-6">
            <label for="novo_unidade_nome" class="font-semibold text-gray-700">Nome da unidade</label>
            <InputText
                id="novo_unidade_nome"
                v-model="nome"
                maxlength="150"
                :class="[
                    'w-full rounded-lg border px-3 py-2',
                    erroNome ? 'border-red-500 focus:border-red-500' : 'border-blue-200 focus:border-blue-500',
                ]"
                placeholder="Digite o nome da unidade"
                :aria-invalid="erroNome ? 'true' : 'false'"
                :invalid="erroNome"
            />
            <label for="novo_unidade_codigo" class="font-semibold text-gray-700">Código</label>
            <InputText
                id="novo_unidade_codigo"
                v-model="codigo"
                maxlength="10"
                :class="[
                    'w-full rounded-lg border px-3 py-2',
                    erroCodigo ? 'border-red-500 focus:border-red-500' : 'border-blue-200 focus:border-blue-500',
                ]"
                placeholder="Digite o código da unidade"
                :aria-invalid="erroCodigo ? 'true' : 'false'"
                :invalid="erroCodigo"
            />
            <transition name="fade">
                <div v-if="erroNome || erroCodigo || erroDuplicado">
                    <p v-if="erroNome" class="mt-1 text-sm text-red-600">O nome é obrigatório.</p>
                    <p v-if="erroCodigo" class="mt-1 text-sm text-red-600">O código é obrigatório.</p>
                    <p v-if="erroDuplicado" class="mt-1 text-sm text-red-600">Já existe uma unidade com esse nome ou código.</p>
                </div>
            </transition>
        </div>
        <template #footer>
            <div class="flex justify-end gap-2 rounded-b-lg bg-gray-50 p-4">
                <Button type="button" @click="$emit('cancel')" variant="outline" class="rounded-lg px-6 py-2">Cancelar</Button>
                <Button
                    type="button"
                    class="ml-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-2 font-semibold text-white shadow-md transition-transform hover:scale-105"
                    @click="salvar"
                >Salvar</Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import axios from 'axios';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: Boolean,
    initialNome: {
        type: String,
        default: '',
    },
    initialCodigo: {
        type: String,
        default: '',
    },
});
const emit = defineEmits(['update:modelValue', 'salvar', 'cancel']);

const nome = ref(props.initialNome);
const codigo = ref(props.initialCodigo);
const erroNome = ref(false);
const erroCodigo = ref(false);
const erroDuplicado = ref(false);

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            nome.value = '';
            codigo.value = '';
            erroNome.value = false;
            erroCodigo.value = false;
            erroDuplicado.value = false;
        }
    },
);

async function salvar() {
    erroNome.value = false;
    erroCodigo.value = false;
    erroDuplicado.value = false;
    if (!nome.value.trim()) {
        erroNome.value = true;
    }
    if (!codigo.value.trim()) {
        erroCodigo.value = true;
    }
    if (erroNome.value || erroCodigo.value) return;
    try {
        const response = await axios.post('/unidades', { nome: nome.value, codigo: codigo.value });
        emit('salvar', response.data);
        emit('update:modelValue', false);
        nome.value = '';
        codigo.value = '';
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            if (error.response.data.errors && (error.response.data.errors.nome || error.response.data.errors.codigo)) {
                erroDuplicado.value = true;
            }
        } else {
            alert('Erro ao salvar unidade.');
        }
    }
}
</script>
