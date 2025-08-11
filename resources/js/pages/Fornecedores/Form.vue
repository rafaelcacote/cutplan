<template>
  <form @submit.prevent="submit">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="space-y-6">
        <div class="space-y-2">
          <label for="nome" class="font-semibold">Nome</label>
          <InputText id="nome" v-model="form.nome" required maxlength="150" class="w-full" />
        </div>
        <div class="space-y-2">
          <label for="documento" class="font-semibold">Documento</label>
          <InputText id="documento" v-model="form.documento" maxlength="32" class="w-full" />
        </div>
        <div class="space-y-2">
          <label for="email" class="font-semibold">Email</label>
          <InputText id="email" v-model="form.email" maxlength="150" class="w-full" />
        </div>
        <div class="space-y-2">
          <label for="telefone" class="font-semibold">Telefone</label>
          <InputText id="telefone" v-model="form.telefone" maxlength="50" class="w-full" />
        </div>
      </div>
      <div class="space-y-6">
        <div class="space-y-2">
          <label for="linha1" class="font-semibold">Endereço</label>
          <InputText id="linha1" v-model="form.endereco.linha1" placeholder="Rua, número" class="w-full" />
        </div>
        <div class="space-y-2">
          <label for="linha2" class="font-semibold">Complemento</label>
          <InputText id="linha2" v-model="form.endereco.linha2" placeholder="Apto, bloco, etc." class="w-full" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="space-y-2">
            <label for="cidade" class="font-semibold">Cidade</label>
            <InputText id="cidade" v-model="form.endereco.cidade" placeholder="Cidade" class="w-full" />
          </div>
          <div class="space-y-2">
            <label for="estado" class="font-semibold">Estado</label>
            <InputText id="estado" v-model="form.endereco.estado" placeholder="Estado" class="w-full" />
          </div>
          <div class="space-y-2">
            <label for="cep" class="font-semibold">CEP</label>
            <InputText id="cep" v-model="form.endereco.cep" placeholder="CEP" class="w-full" />
          </div>
        </div>
        <div class="space-y-2">
          <label for="pais" class="font-semibold">País</label>
          <InputText id="pais" v-model="form.endereco.pais" placeholder="País" class="w-full" />
        </div>
      </div>
    </div>
    <div class="md:col-span-2 col-span-1 space-y-2 mt-6">
      <label for="observacoes" class="font-semibold">Observações</label>
      <Textarea id="observacoes" v-model="form.observacoes" rows="3" class="w-full min-h-[90px]" autoResize />
    </div>
    <div class="flex items-center gap-4 justify-end mt-8">
      <UiButton type="submit" :disabled="form.processing" size="lg" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 px-8 py-3 text-lg font-semibold transition-transform duration-300 hover:scale-105">
        Salvar
      </UiButton>
    </div>
  </form>
</template>

<script setup>
import { reactive } from 'vue';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { Button as UiButton } from '@/components/ui/button';

const props = defineProps({ fornecedor: Object });
const emit = defineEmits(['submit']);

const form = reactive({
  nome: props.fornecedor?.nome || '',
  documento: props.fornecedor?.documento || '',
  email: props.fornecedor?.email || '',
  telefone: props.fornecedor?.telefone || '',
  endereco: {
    linha1: props.fornecedor?.endereco?.linha1 || '',
    linha2: props.fornecedor?.endereco?.linha2 || '',
    cidade: props.fornecedor?.endereco?.cidade || '',
    estado: props.fornecedor?.endereco?.estado || '',
    cep: props.fornecedor?.endereco?.cep || '',
    pais: props.fornecedor?.endereco?.pais || '',
  },
  observacoes: props.fornecedor?.observacoes || '',
  processing: false,
});

function submit() {
  form.processing = true;
  emit('submit', { ...form });
  form.processing = false;
}
</script>
