<template>
    <Head title="Editar Usuário" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col gap-6 bg-[#F8FAFC] p-4 transition-colors duration-300 md:p-8 dark:bg-[#1E293B]">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Editar Usuário</h1>
                    <p class="text-[#64748B] dark:text-[#CBD5E1]">Atualize os dados do usuário</p>
                </div>
            </div>
            <Card class="surface-border rounded-2xl border-1 border-[#E0E7EF] bg-white shadow-2xl dark:border-[#334155] dark:bg-[#1E293B]">
                <CardContent class="p-6">
                    <UserForm :user="user" @saved="onSaved" />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import UserForm from './UserForm.vue';

const props = defineProps<{ user: any }>();

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Usuários', href: '/usuarios' },
    { title: 'Editar', href: '' },
];

function onSaved() {
    router.visit('/usuarios');
}
</script>
