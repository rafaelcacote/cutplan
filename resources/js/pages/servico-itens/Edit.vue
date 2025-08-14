<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import AutoComplete from 'primevue/autocomplete';
import InputNumber from 'primevue/inputnumber';
import Checkbox from 'primevue/checkbox';
import { ArrowLeft, Save, Search } from 'lucide-vue-next';
import axios from 'axios';

interface Servico {
    id: number;
    nome: string;
}

interface ServicoItem {
    id: number;
    servico_id: number;
    descricao_item: string;
    ordem?: number;
    opcional: boolean;
    servico: Servico;
}

interface ServicoOption {
    value: number;
    label: string;
}

interface Props {
    servicoItem: ServicoItem;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Itens de Serviço',
        href: '/servico-itens',
    },
    {
        title: 'Editar Item',
        href: `/servico-itens/${props.servicoItem.id}/edit`,
    },
];

const form = useForm({
    servico_id: props.servicoItem.servico_id,
    descricao_item: props.servicoItem.descricao_item,
    ordem: props.servicoItem.ordem,
    opcional: props.servicoItem.opcional,
});

// Estados para AutoComplete de Serviços
const servicoOptions = ref<ServicoOption[]>([]);
const servicoSuggestions = ref<ServicoOption[]>([]);
const selectedServico = ref<string>(props.servicoItem.servico.nome);
const servicoLoading = ref(false);

const toast = useToast();

onMounted(() => {
    // Inicializa o serviço selecionado
    if (props.servicoItem.servico) {
        selectedServico.value = props.servicoItem.servico.nome;
    }
});

// Busca serviços para o AutoComplete
const searchServicos = async (event: any) => {
    const query = event.query?.trim();
    
    if (!query || query.length < 2) {
        servicoSuggestions.value = [];
        return;
    }

    servicoLoading.value = true;
    
    try {
        const response = await axios.get('/autocomplete/servicos', {
            params: { query }
        });
        servicoSuggestions.value = response.data;
    } catch (error) {
        console.error('Erro ao buscar serviços:', error);
        servicoSuggestions.value = [];
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Erro ao buscar serviços. Tente novamente.',
            life: 4000,
            group: 'main'
        });
    } finally {
        servicoLoading.value = false;
    }
};

// Quando um serviço é selecionado
const onServicoSelect = (event: any) => {
    const selectedOption = event.value;
    selectedServico.value = selectedOption.label;
    form.servico_id = selectedOption.value;
};

// Quando o campo de serviço é limpo
const onServicoInput = (value: any) => {
    if (!value || (typeof value === 'string' && value.trim() === '')) {
        selectedServico.value = '';
        form.servico_id = null;
    }
};const handleSubmit = () => {
    // Validação básica
    if (!form.servico_id) {
        toast.add({
            severity: 'error',
            summary: 'Erro de Validação',
            detail: 'Por favor, selecione um serviço.',
            life: 4000,
            group: 'main'
        });
        return;
    }

    if (!form.descricao_item.trim()) {
        toast.add({
            severity: 'error',
            summary: 'Erro de Validação',
            detail: 'Por favor, informe a descrição do item.',
            life: 4000,
            group: 'main'
        });
        return;
    }

    form.put(`/servico-itens/${props.servicoItem.id}`, {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Sucesso',
                detail: 'Item de serviço atualizado com sucesso!',
                life: 4000,
                group: 'main'
            });
        },
        onError: (errors) => {
            // Mostra os erros de validação
            Object.values(errors).forEach((error: any) => {
                toast.add({
                    severity: 'error',
                    summary: 'Erro de Validação',
                    detail: error,
                    life: 5000,
                    group: 'main'
                });
            });
        }
    });
};

const isFormValid = computed(() => {
    return form.servico_id && form.descricao_item.trim();
});
</script>

<template>
  <Head title="Editar Item de Serviço" />
  <Toast position="top-right" group="main" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4 md:p-8 bg-[#F8FAFC] min-h-screen dark:bg-[#1E293B] transition-colors duration-300">
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA]">Editar Item de Serviço</h1>
          <p class="text-[#64748B] dark:text-[#CBD5E1]">Altere as informações do item de serviço</p>
        </div>
        <Button variant="outline" as-child class="border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300">
          <Link :href="route('servico-itens.index')">
            <ArrowLeft class="mr-2 h-4 w-4" />
            Voltar
          </Link>
        </Button>
      </div>

      <!-- Formulário -->
      <Card class="shadow-2xl border-1 surface-border border-[#E0E7EF] dark:border-[#334155] bg-white dark:bg-[#1E293B] rounded-2xl transition-all duration-300">
        <CardHeader>
          <CardTitle class="text-[#1E293B] dark:text-white">Informações do Item</CardTitle>
          <CardDescription class="text-[#64748B] dark:text-[#CBD5E1]">
            Altere as informações do item de serviço
          </CardDescription>
        </CardHeader>
        <CardContent class="space-y-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Serviço -->
            <div class="space-y-2">
              <Label for="servico" class="text-sm font-medium text-[#1E293B] dark:text-white">
                Serviço <span class="text-red-500">*</span>
              </Label>
              <div class="space-y-2">
                <AutoComplete
                  id="servico"
                  v-model="selectedServico"
                  :suggestions="servicoSuggestions"
                  @complete="searchServicos"
                  @item-select="onServicoSelect"
                  @input="onServicoInput"
                  option-label="label"
                  placeholder="Digite para buscar um serviço..."
                  class="w-full"
                  :class="{ 'p-invalid': form.errors.servico_id }"
                  :loading="servicoLoading"
                  dropdown
                />
                <p class="text-xs text-[#64748B] dark:text-[#CBD5E1] flex items-center gap-1">
                  <Search class="w-3 h-3" />
                  Digite pelo menos 2 caracteres para buscar serviços
                </p>
              </div>
              <div v-if="form.errors.servico_id" class="text-red-500 text-sm">{{ form.errors.servico_id }}</div>
            </div>

            <!-- Descrição do Item -->
            <div class="space-y-2">
              <Label for="descricao_item" class="text-sm font-medium text-[#1E293B] dark:text-white">
                Descrição do Item <span class="text-red-500">*</span>
              </Label>
              <Textarea
                id="descricao_item"
                v-model="form.descricao_item"
                placeholder="Descreva detalhadamente este item do serviço..."
                class="min-h-[100px] resize-none"
                :class="{ 'border-red-500': form.errors.descricao_item }"
              />
              <div v-if="form.errors.descricao_item" class="text-red-500 text-sm">{{ form.errors.descricao_item }}</div>
            </div>

            <!-- Ordem e Opcional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Ordem -->
              <div class="space-y-2">
                <Label for="ordem" class="text-sm font-medium text-[#1E293B] dark:text-white">
                  Ordem de Execução
                </Label>
                <InputNumber
                  id="ordem"
                  v-model="form.ordem"
                  placeholder="Ex: 1, 2, 3..."
                  class="w-full"
                  :class="{ 'p-invalid': form.errors.ordem }"
                  :min="1"
                  :useGrouping="false"
                />
                <p class="text-xs text-[#64748B] dark:text-[#CBD5E1]">
                  Define a ordem de execução deste item no serviço
                </p>
                <div v-if="form.errors.ordem" class="text-red-500 text-sm">{{ form.errors.ordem }}</div>
              </div>

              <!-- Opcional -->
              <div class="space-y-2">
                <Label for="opcional" class="text-sm font-medium text-[#1E293B] dark:text-white">
                  Tipo do Item
                </Label>
                <div class="flex items-center space-x-3 p-4 bg-[#F8FAFC] dark:bg-[#334155] rounded-lg border">
                  <Checkbox
                    id="opcional"
                    v-model="form.opcional"
                    :binary="true"
                    class="shrink-0"
                  />
                  <div class="flex-1">
                    <label for="opcional" class="text-sm font-medium text-[#1E293B] dark:text-white cursor-pointer">
                      {{ form.opcional ? 'Item Opcional' : 'Item Obrigatório' }}
                    </label>
                    <p class="text-xs text-[#64748B] dark:text-[#CBD5E1] mt-1">
                      {{ form.opcional 
                        ? 'Este item pode ser pulado durante a execução do serviço' 
                        : 'Este item é obrigatório para a execução do serviço' 
                      }}
                    </p>
                  </div>
                </div>
                <div v-if="form.errors.opcional" class="text-red-500 text-sm">{{ form.errors.opcional }}</div>
              </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex justify-end gap-4 pt-6 border-t border-[#E0E7EF] dark:border-[#334155]">
              <Button 
                type="button" 
                variant="outline" 
                as-child
                class="px-6 py-2 border-[#3B82F6] text-[#3B82F6] dark:text-[#60A5FA] hover:bg-[#EFF6FF] dark:hover:bg-[#334155] rounded-lg transition-all duration-300"
              >
                <Link :href="route('servico-itens.index')">
                  Cancelar
                </Link>
              </Button>
              <Button 
                type="submit" 
                :disabled="!isFormValid || form.processing"
                class="px-6 py-2 bg-gradient-to-r from-[#3B82F6] to-[#6366F1] text-white shadow-lg border-0 rounded-lg font-semibold transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
              >
                <Save class="mr-2 h-4 w-4" />
                {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<style scoped>
.p-autocomplete, .p-inputnumber, .p-checkbox {
  border-radius: 0.75rem !important;
}

.p-autocomplete-panel {
  border-radius: 0.75rem !important;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

.p-autocomplete-items .p-autocomplete-item {
  padding: 0.75rem 1rem !important;
  transition: all 0.2s !important;
}

.p-autocomplete-items .p-autocomplete-item:hover {
  background: linear-gradient(90deg, #3B82F6 0%, #6366F1 100%) !important;
  color: #fff !important;
}

.p-checkbox .p-checkbox-box {
  border-radius: 0.375rem !important;
  border: 2px solid #E0E7EF !important;
  transition: all 0.3s !important;
}

.p-checkbox .p-checkbox-box.p-highlight {
  background: linear-gradient(90deg, #3B82F6 0%, #6366F1 100%) !important;
  border-color: transparent !important;
}

.p-inputnumber-input {
  border-radius: 0.75rem !important;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
