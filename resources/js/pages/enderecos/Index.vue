<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps<{ enderecos: any }>();

const destroy = (id: number) => {
    if (confirm('Tem certeza que deseja remover este endereço?')) {
        router.delete(route('enderecos.destroy', id));
    }
};
</script>

<template>
    <div class="flex flex-col gap-6 p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Endereços</h1>
            <Button as-child>
                <Link :href="route('enderecos.create')">Novo Endereço</Link>
            </Button>
        </div>
        <div class="overflow-x-auto rounded-lg border bg-white dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-muted">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Linha 1</th>
                        <th class="px-4 py-2 text-left">Linha 2</th>
                        <th class="px-4 py-2 text-left">Cidade</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">CEP</th>
                        <th class="px-4 py-2 text-left">País</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="endereco in enderecos.data" :key="endereco.id" class="hover:bg-muted/50">
                        <td class="px-4 py-2">{{ endereco.linha1 }}</td>
                        <td class="px-4 py-2">{{ endereco.linha2 }}</td>
                        <td class="px-4 py-2">{{ endereco.cidade }}</td>
                        <td class="px-4 py-2">{{ endereco.estado }}</td>
                        <td class="px-4 py-2">{{ endereco.cep }}</td>
                        <td class="px-4 py-2">{{ endereco.pais }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <Button as-child size="sm" variant="outline">
                                <Link :href="route('enderecos.edit', endereco.id)">Editar</Link>
                            </Button>
                            <Button size="sm" variant="destructive" @click="destroy(endereco.id)">Excluir</Button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="enderecos.links" class="flex justify-center mt-4 gap-2">
            <Link v-for="link in enderecos.links" :key="link.label" :href="link.url || '#'" v-html="link.label"
                :class="['px-3 py-1 rounded', link.active ? 'bg-primary text-white' : 'hover:bg-muted']" />
        </div>
    </div>
</template>
