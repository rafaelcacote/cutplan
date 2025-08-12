<template>
    <form @submit.prevent="saveRole" class="space-y-6">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-[#1E293B] dark:text-white">Nome do Perfil</label>
            <InputText id="name" v-model="form.name" required maxlength="255" class="w-full" placeholder="Digite o nome do perfil" />
        </div>

        <div class="space-y-2">
            <label for="permissions" class="font-semibold text-[#1E293B] dark:text-white">Permissões</label>
            <MultiSelect
                id="permissions"
                v-model="form.permissions"
                :options="permissionsOptions"
                optionLabel="name"
                optionValue="name"
                placeholder="Selecione as permissões"
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
import { onMounted, ref, watch } from 'vue';
import api from '../../api';

const props = defineProps({
    role: { type: Object, default: null },
});

const emit = defineEmits(['saved']);

const form = ref({
    id: null,
    name: '',
    permissions: [] as string[],
});

const permissionsOptions = ref<{ name: string; id: number }[]>([]);

const fetchPermissions = async () => {
    const response = await api.get('/permissions');
    permissionsOptions.value = response.data;
};

watch(
    () => props.role,
    (role) => {
        if (role) {
            form.value = {
                id: role.id,
                name: role.name,
                permissions: role.permissions ? role.permissions.map((p: any) => p.name) : [],
            };
        } else {
            form.value = { id: null, name: '', permissions: [] };
        }
    },
    { immediate: true },
);

const saveRole = () => {
    if (form.value.id) {
        api.put(`/roles/${form.value.id}`, form.value).then(() => emit('saved'));
    } else {
        api.post('/roles', form.value).then(() => emit('saved'));
    }
};

onMounted(fetchPermissions);
</script>
