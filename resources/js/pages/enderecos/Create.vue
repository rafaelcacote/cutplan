<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const form = useForm({
    linha1: '',
    linha2: '',
    cidade: '',
    estado: '',
    cep: '',
    pais: 'Brasil',
});

const submit = () => {
    form.post(route('enderecos.store'));
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Novo Endereço</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <Label for="linha1">Linha 1</Label>
                    <Input id="linha1" v-model="form.linha1" required />
                </div>
                <div>
                    <Label for="linha2">Linha 2</Label>
                    <Input id="linha2" v-model="form.linha2" />
                </div>
                <div>
                    <Label for="cidade">Cidade</Label>
                    <Input id="cidade" v-model="form.cidade" required />
                </div>
                <div>
                    <Label for="estado">Estado</Label>
                    <Input id="estado" v-model="form.estado" required />
                </div>
                <div>
                    <Label for="cep">CEP</Label>
                    <Input id="cep" v-model="form.cep" />
                </div>
                <div>
                    <Label for="pais">País</Label>
                    <Input id="pais" v-model="form.pais" />
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <Button type="submit" :disabled="form.processing">Salvar</Button>
                <Button type="button" variant="outline" @click="router.visit(route('enderecos.index'))">Cancelar</Button>
            </div>
        </form>
    </div>
</template>
