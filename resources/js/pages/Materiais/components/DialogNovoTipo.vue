<template>
    <Dialog
        :visible="modelValue"
        @update:visible="(val) => $emit('update:modelValue', val)"
        modal
        header="Novo Tipo de Material"
        :style="{ width: '340px', borderRadius: '14px', padding: '0' }"
        contentClass="p-0"
    >
        <form @submit.prevent="salvar">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4">
                <div class="col-span-1 sm:col-span-2 flex flex-col">
                    <label for="novo_tipo_nome" class="font-semibold text-gray-700 mb-1">Nome do tipo</label>
                    <InputText
                        id="novo_tipo_nome"
                        v-model="nome"
                        maxlength="150"
                        :class="[
                            'w-full rounded-md border px-3 py-2 text-sm',
                            erroNome ? 'border-red-500 focus:border-red-500' : 'border-blue-200 focus:border-blue-500',
                        ]"
                        placeholder="Digite o nome do tipo"
                        :aria-invalid="erroNome ? 'true' : 'false'"
                        :invalid="erroNome"
                    />
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <transition name="fade">
                        <div v-if="erroNome || erroDuplicado">
                            <p v-if="erroNome" class="mt-1 text-xs text-red-600">O nome é obrigatório.</p>
                            <p v-if="erroDuplicado" class="mt-1 text-xs text-red-600">Já existe um tipo de material com esse nome.</p>
                        </div>
                    </transition>
                </div>
            </div>
            <div class="flex justify-end gap-2 rounded-b-lg bg-gray-50 px-4 py-3">
                <Button type="button" @click="$emit('cancel')" variant="outline" class="rounded-lg px-4 py-2 text-sm">Cancelar</Button>
                <Button
                    type="submit"
                    class="ml-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition-transform hover:scale-105"
                >Salvar</Button>
            </div>
        </form>
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
});
const emit = defineEmits(['update:modelValue', 'salvar', 'cancel']);

const nome = ref(props.initialNome);
const erroNome = ref(false);

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            nome.value = '';
            erroNome.value = false;
        }
    },
);

const erroDuplicado = ref(false);

async function salvar() {
    erroNome.value = false;
    erroDuplicado.value = false;
    if (!nome.value.trim()) {
        erroNome.value = true;
        return;
    }
    try {
        const response = await axios.post('/tipos-materiais', { nome: nome.value });
        emit('salvar', response.data.nome);
        emit('update:modelValue', false);
        nome.value = '';
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            if (error.response.data.errors && error.response.data.errors.nome) {
                erroDuplicado.value = true;
            }
        } else {
            alert('Erro ao salvar tipo de material.');
        }
    }
}
</script>
