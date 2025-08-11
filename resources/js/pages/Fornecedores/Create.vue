<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA] mb-6">Novo Fornecedor</h1>
      <FornecedorForm :enderecos="enderecos" @submit="handleSubmit" />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import FornecedorForm from './Form.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({ enderecos: Array });

const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Fornecedores', href: '/fornecedores' },
  { title: 'Novo Fornecedor', href: null },
];

function handleSubmit(data) {
  router.post('/fornecedores', data);
}
</script>
