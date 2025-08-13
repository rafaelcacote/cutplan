<template>
    <form @submit.prevent="saveRole" class="space-y-6">
        <div class="space-y-2">
            <label for="name" class="font-semibold text-[#1E293B] dark:text-white">Nome do Perfil</label>
            <InputText id="name" v-model="form.name" required maxlength="255" class="w-full" placeholder="Digite o nome do perfil" />
        </div>

        <div class="space-y-3">
            <label class="font-semibold text-[#1E293B] dark:text-white">Permissões</label>
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div v-for="mod in modules" :key="mod.name" class="rounded-xl border border-[#E0E7EF] dark:border-[#334155] overflow-hidden">
                    <div class="flex items-center justify-between bg-[#F8FAFC] dark:bg-[#0F172A] px-4 py-3">
                        <div class="font-semibold text-[#1E293B] dark:text-white">{{ mod.label }}</div>
                        <div class="flex items-center gap-2 text-sm">
                            <Checkbox :binary="true" :modelValue="isModuleAllSelected(mod.name)" @update:modelValue="toggleModule(mod.name)" />
                            <span class="text-[#64748B] dark:text-[#CBD5E1]">Selecionar todos</span>
                        </div>
                    </div>
                    <div class="p-4 space-y-2">
                        <div v-for="perm in mod.permissions" :key="perm.id" class="flex items-center gap-2">
                            <Checkbox :inputId="`perm-${perm.id}`" :value="perm.name" v-model="form.permissions" />
                            <label :for="`perm-${perm.id}`" class="text-sm text-[#1E293B] dark:text-white">{{ perm.name }}</label>
                        </div>
                    </div>
                </div>
            </div>
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
import Checkbox from 'primevue/checkbox';
import { computed, onMounted, ref, watch } from 'vue';
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
    const response = await api.get('/api/permissions');
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
        api.put(`/api/roles/${form.value.id}`, form.value).then(() => emit('saved'));
    } else {
        api.post('/api/roles', form.value).then(() => emit('saved'));
    }
};

onMounted(fetchPermissions);

// Helpers de agrupamento por módulo
const modules = computed(() => {
    const groups: Record<string, { name: string; label: string; permissions: { id: number; name: string }[] }> = {};
    for (const p of permissionsOptions.value) {
        // regra: último token do nome é o módulo (ex: "view users" -> users)
        const parts = p.name.split(/\s+/);
        const moduleName = parts[parts.length - 1];
        if (!groups[moduleName]) {
            groups[moduleName] = {
                name: moduleName,
                label: moduleName.charAt(0).toUpperCase() + moduleName.slice(1),
                permissions: [],
            };
        }
        groups[moduleName].permissions.push({ id: p.id, name: p.name });
    }
    return Object.values(groups).sort((a, b) => a.label.localeCompare(b.label));
});

const isModuleAllSelected = (moduleName: string) => {
    const mod = modules.value.find((m) => m.name === moduleName);
    if (!mod) return false;
    return mod.permissions.every((perm) => form.value.permissions.includes(perm.name));
};

const toggleModule = (moduleName: string) => {
    const mod = modules.value.find((m) => m.name === moduleName);
    if (!mod) return;
    const allSelected = isModuleAllSelected(moduleName);
    if (allSelected) {
        form.value.permissions = form.value.permissions.filter((p) => !mod.permissions.some((mp) => mp.name === p));
    } else {
        const names = mod.permissions.map((p) => p.name);
        form.value.permissions = Array.from(new Set([...form.value.permissions, ...names]));
    }
};
</script>
