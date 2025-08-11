<template>
    <div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="nome" class="font-semibold">Nome</label>
                    <InputText id="nome" v-model="localForm.nome" required maxlength="150" class="w-full" @input="emitChange" />
                </div>
                <div class="space-y-2">
                    <label for="documento" class="font-semibold">Documento</label>
                    <InputText id="documento" v-model="localForm.documento" maxlength="32" class="w-full" @input="emitChange" />
                </div>
                <div class="space-y-2">
                    <label for="email" class="font-semibold">Email</label>
                    <InputText id="email" v-model="localForm.email" maxlength="150" class="w-full" @input="emitChange" />
                </div>
                <div class="space-y-2">
                    <label for="telefone" class="font-semibold">Telefone</label>
                    <InputText id="telefone" v-model="localForm.telefone" maxlength="50" class="w-full" @input="emitChange" />
                </div>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label for="linha1" class="font-semibold">Endereço</label>
                    <InputText id="linha1" v-model="localForm.endereco.linha1" placeholder="Rua, número" class="w-full" @input="emitChange" />
                </div>
                <div class="space-y-2">
                    <label for="linha2" class="font-semibold">Complemento</label>
                    <InputText id="linha2" v-model="localForm.endereco.linha2" placeholder="Apto, bloco, etc." class="w-full" @input="emitChange" />
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="space-y-2">
                        <label for="cidade" class="font-semibold">Cidade</label>
                        <InputText id="cidade" v-model="localForm.endereco.cidade" placeholder="Cidade" class="w-full" @input="emitChange" />
                    </div>
                    <div class="space-y-2">
                        <label for="estado" class="font-semibold">Estado</label>
                        <InputText id="estado" v-model="localForm.endereco.estado" placeholder="Estado" class="w-full" @input="emitChange" />
                    </div>
                    <div class="space-y-2">
                        <label for="cep" class="font-semibold">CEP</label>
                        <InputText id="cep" v-model="localForm.endereco.cep" placeholder="CEP" class="w-full" @input="emitChange" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="pais" class="font-semibold">País</label>
                    <InputText id="pais" v-model="localForm.endereco.pais" placeholder="País" class="w-full" @input="emitChange" />
                </div>
            </div>
        </div>
        <div class="col-span-1 mt-6 space-y-2 md:col-span-2">
            <label for="observacoes" class="font-semibold">Observações</label>
            <Textarea id="observacoes" v-model="localForm.observacoes" rows="3" class="min-h-[90px] w-full" autoResize @input="emitChange" />
        </div>
    </div>
</template>

<script setup>
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { reactive, watch } from 'vue';

const props = defineProps({
    modelValue: Object,
    enderecos: Array,
});
const emit = defineEmits(['update:modelValue']);

const localForm = reactive({
    nome: '',
    documento: '',
    email: '',
    telefone: '',
    endereco: {
        linha1: '',
        linha2: '',
        cidade: '',
        estado: '',
        cep: '',
        pais: '',
    },
    observacoes: '',
});

// Sincroniza modelValue -> localForm
watch(
    () => props.modelValue,
    (val) => {
        if (val) {
            Object.assign(localForm, JSON.parse(JSON.stringify(val)));
        }
    },
    { immediate: true, deep: true },
);

function emitChange() {
    emit('update:modelValue', JSON.parse(JSON.stringify(localForm)));
}
</script>
