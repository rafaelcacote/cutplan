<template>
    <form @submit.prevent="saveUser" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="name" class="font-semibold text-[#1E293B] dark:text-white">Nome</label>
                <InputText id="name" v-model="form.name" required maxlength="255" class="w-full" placeholder="Digite o nome completo" />
            </div>
            <div class="space-y-2">
                <label for="email" class="font-semibold text-[#1E293B] dark:text-white">E-mail</label>
                <InputText id="email" v-model="form.email" required type="email" maxlength="255" class="w-full" placeholder="Digite o e-mail" />
            </div>
        </div>

        <div class="space-y-2" v-if="!form.id">
            <label for="password" class="font-semibold text-[#1E293B] dark:text-white">Senha</label>
            <Password id="password" v-model="form.password" required toggleMask class="w-full" placeholder="Digite a senha" :feedback="false" />
        </div>

        <div class="space-y-2">
            <label for="roles" class="font-semibold text-[#1E293B] dark:text-white">Perfis</label>
            <MultiSelect
                id="roles"
                v-model="form.roles"
                :options="rolesOptions"
                optionLabel="name"
                optionValue="name"
                placeholder="Selecione os perfis"
                class="w-full"
                display="chip"
            />
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <Button type="submit" class="bg-gradient-to-r from-[#3B82F6] to-[#6366F1] px-6 text-white">
                <Check class="mr-2 h-4 w-4" />
                Salvar
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Check } from 'lucide-vue-next';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Password from 'primevue/password';
import { onMounted, ref, watch } from 'vue';
import api from '../../api';

const props = defineProps({
    user: { type: Object, default: null },
});

const emit = defineEmits(['saved']);

interface UserFormType {
    id: number | null;
    name: string;
    email: string;
    password: string;
    roles: string[];
}

const form = ref<UserFormType>({
    id: null,
    name: '',
    email: '',
    password: '',
    roles: [],
});

const rolesOptions = ref<{ name: string; id: number }[]>([]);

const fetchRoles = async () => {
    const response = await api.get('/api/usuarios/roles');
    rolesOptions.value = response.data;
};

watch(
    () => props.user,
    (user) => {
        if (user) {
            form.value = {
                id: user.id,
                name: user.name,
                email: user.email,
                password: '',
                roles: user.roles || [],
            };
        } else {
            form.value = { id: null, name: '', email: '', password: '', roles: [] };
        }
    },
    { immediate: true },
);

const saveUser = async () => {
    if (form.value.id) {
        await api.put(`/api/usuarios/${form.value.id}`, form.value);
        emit('saved');
    } else {
        await api.post('/api/usuarios', form.value);
        emit('saved');
    }
};

onMounted(fetchRoles);
</script>
