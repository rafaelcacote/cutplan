<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

import { Save, X, Package } from 'lucide-vue-next';
import AutoComplete from 'primevue/autocomplete';
import ButtonPrime from 'primevue/button';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import Steps from 'primevue/steps';
import { computed, ref, onMounted, nextTick } from 'vue';

const steps = [
    { label: 'Dados do Orçamento' },
    { label: 'Serviços' },
    { label: 'Totais' },
];
const activeStep = ref(0);

function nextStep() {
    if (activeStep.value < steps.length - 1) activeStep.value++;
}

function prevStep() {
    if (activeStep.value > 0) activeStep.value--;
}


interface Cliente {
    id: number;
    nome: string;
    documento?: string;
    email?: string;
}

interface ServicoOrcamento {
    id?: number;
    nome: string;
    itens: string[];
    valor_total: number;
    observacoes?: string;
}

interface Props {
    orcamento?: {
        id: number;
        cliente_id: number;
        status: string;
        moeda: string;
        desconto: string;
        impostos: string;
        validade?: string;
        observacoes?: string;
        cliente: Cliente;
        servicos: ServicoOrcamento[];
    };
}

const props = defineProps<Props>();

const isEdit = computed(() => !!props.orcamento);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Orçamentos', href: '/orcamentos' },
    { title: isEdit.value ? 'Editar Orçamento' : 'Novo Orçamento', href: '#' },
];

// Form data
const formData = ref({
    cliente_id: props.orcamento?.cliente_id || null,
    status: props.orcamento?.status || 'draft',
    moeda: props.orcamento?.moeda || 'BRL',
    desconto: parseFloat(props.orcamento?.desconto || '0'),
    impostos: parseFloat(props.orcamento?.impostos || '0'),
    validade: props.orcamento?.validade ? new Date(props.orcamento.validade) : null,
    observacoes: props.orcamento?.observacoes || '',
});

const form = useForm(formData.value);

// Estados para autocomplete
const clientesSugestoes = ref<Cliente[]>([]);
const clienteCarregando = ref(false);

// Lista de serviços do orçamento
const servicos = ref<ServicoOrcamento[]>(props.orcamento?.servicos || []);

// Novo serviço sendo criado

// Autocomplete para serviço
const servicoSugestoes = ref<any[]>([]);
const servicoCarregando = ref(false);
const buscarServicos = async (event: any) => {
    servicoCarregando.value = true;
    try {
        const response = await fetch(`/autocomplete/servicos?search=${encodeURIComponent(event.query)}`);
        servicoSugestoes.value = await response.json();
    } catch (error) {
        console.error('Erro ao buscar serviços:', error);
    } finally {
        servicoCarregando.value = false;
    }
};

// Autocomplete para itens de serviço
const itensSugestoes = ref<any[]>([]);
const itensCarregando = ref(false);
const buscarItensServico = async (event: any) => {
    itensCarregando.value = true;
    try {
        const response = await fetch(`/autocomplete/servico-itens?search=${encodeURIComponent(event.query)}`);
        let data = await response.json();
        // Garante que cada sugestão seja { nome: string } baseado em descricao_item
        if (Array.isArray(data)) {
            itensSugestoes.value = data.map((item) => {
                if (typeof item === 'string') {
                    return { nome: item };
                } else if (item && typeof item === 'object' && 'descricao_item' in item) {
                    return { nome: item.descricao_item };
                } else if (item && typeof item === 'object' && 'nome' in item) {
                    return { nome: item.nome };
                } else if (item && typeof item === 'object') {
                    // tenta pegar descricao_item ou o primeiro valor string
                    const descricao = item.descricao_item || Object.values(item).find((v) => typeof v === 'string');
                    return { nome: descricao || '' };
                }
                return { nome: '' };
            });
        } else {
            itensSugestoes.value = [];
        }
    } catch (error) {
        console.error('Erro ao buscar itens de serviço:', error);
        itensSugestoes.value = [];
    } finally {
        itensCarregando.value = false;
    }
};

const novoServico = ref<ServicoOrcamento>({
    nome: '', // será o nome do serviço selecionado
    id: undefined,
    itens: [],
    valor_total: 0,
    observacoes: '',
});

// Novo item sendo adicionado ao serviço atual (autocomplete)
const novoItem = ref<string | any>('');

const statusOptions = [
    { label: 'Rascunho', value: 'draft' },
    { label: 'Enviado', value: 'sent' },
    { label: 'Aprovado', value: 'approved' },
    { label: 'Rejeitado', value: 'rejected' },
    { label: 'Expirado', value: 'expired' },
];

const toast = useToast();

// Funções de autocomplete
const buscarClientes = async (event: any) => {
    clienteCarregando.value = true;
    try {
        const response = await fetch(`/autocomplete/clientes?q=${encodeURIComponent(event.query)}`);
        clientesSugestoes.value = await response.json();
    } catch (error) {
        console.error('Erro ao buscar clientes:', error);
    } finally {
        clienteCarregando.value = false;
    }
};

// Funções de gerenciamento de serviços

const adicionarItem = () => {
    // Extrai o valor correto, seja string ou objeto
    let itemValue = '';

    if (typeof novoItem.value === 'string') {
        itemValue = novoItem.value;
    } else if (novoItem.value && typeof novoItem.value === 'object') {
        // Tenta extrair 'nome' do objeto
        itemValue = (novoItem.value as any).nome || String(novoItem.value);
    }

    if (!itemValue || itemValue.trim() === '') {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Selecione um item do serviço.',
            life: 3000,
        });
        return;
    }

    // Evita duplicidade
    if (novoServico.value.itens.includes(itemValue)) {
        toast.add({
            severity: 'warn',
            summary: 'Aviso',
            detail: 'Este item já foi adicionado.',
            life: 2000,
        });
        return;
    }

    novoServico.value.itens.push(itemValue);
    novoItem.value = '';
};

const removerItem = (index: number) => {
    novoServico.value.itens.splice(index, 1);
};

const adicionarServico = () => {
    // Garante que temos apenas o nome do serviço (string)
    let nomeServico = '';
    if (typeof novoServico.value.nome === 'string') {
        nomeServico = novoServico.value.nome;
    } else if (novoServico.value.nome && typeof novoServico.value.nome === 'object') {
        nomeServico = (novoServico.value.nome as any).nome || '';
    }

    if (!nomeServico || novoServico.value.itens.length === 0 || novoServico.value.valor_total <= 0) {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Selecione o serviço, adicione pelo menos um item e defina o valor.',
            life: 3000,
        });
        return;
    }

    // Cria o serviço com apenas o nome (string)
    const servicoParaAdicionar: ServicoOrcamento = {
        nome: nomeServico,
        itens: [...novoServico.value.itens],
        valor_total: novoServico.value.valor_total,
        observacoes: novoServico.value.observacoes,
    };

    servicos.value.push(servicoParaAdicionar);

    // Reset novo serviço
    novoServico.value = {
        nome: '',
        id: undefined,
        itens: [],
        valor_total: 0,
        observacoes: '',
    };
};

const removerServico = (index: number) => {
    servicos.value.splice(index, 1);
};

// Calculados
const subtotal = computed(() => {
    return servicos.value.reduce((acc, servico) => acc + servico.valor_total, 0);
});

const total = computed(() => {
    return subtotal.value - formData.value.desconto + formData.value.impostos;
});

// Funções de formulário
const submit = () => {
    // Garante que cliente_id seja sempre um número
    if (formData.value.cliente_id && typeof formData.value.cliente_id === 'object') {
        formData.value.cliente_id = formData.value.cliente_id.id;
    }
    const data = {
        ...formData.value,
        moeda: 'BRL',
        servicos: servicos.value,
    };

    if (isEdit.value) {
        form.transform(() => data).put(`/orcamentos/${props.orcamento!.id}`, {
            preserveScroll: true,
        });
    } else {
        form.transform(() => data).post('/orcamentos-simples', {
            preserveScroll: true,
        });
    }
};

// Formatação
const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value);
};

// Refs para inputs
const clienteInputRef = ref();
const statusInputRef = ref();
const validadeInputRef = ref();
const observacoesInputRef = ref();

// Refs para inputs do segundo stepper
const nomeServicoRef = ref();
const itensServicoRef = ref();
const btnAdicionarRef = ref();

function focusItensServico() {
    nextTick(() => {
        if (itensServicoRef.value?.$el?.querySelector('input')) {
            itensServicoRef.value.$el.querySelector('input').focus();
        }
    });
}
function focusBtnAdicionar() {
    nextTick(() => {
        if (btnAdicionarRef.value?.$el) {
            btnAdicionarRef.value.$el.focus();
        }
    });
}

// Função para focar próximo campo
function focusNext(refName: string) {
    nextTick(() => {
        if (refName === 'statusInputRef' && statusInputRef.value?.$el?.querySelector('input')) {
            statusInputRef.value.$el.querySelector('input').focus();
        } else if (refName === 'validadeInputRef' && validadeInputRef.value?.$el?.querySelector('input')) {
            validadeInputRef.value.$el.querySelector('input').focus();
        } else if (refName === 'observacoesInputRef' && observacoesInputRef.value?.$el) {
            observacoesInputRef.value.$el.focus();
        }
    });
}

// Autofoco ao entrar na tela
onMounted(() => {
    nextTick(() => {
        if (clienteInputRef.value?.$el?.querySelector('input')) {
            clienteInputRef.value.$el.querySelector('input').focus();
        }
    });
});

defineExpose({
    activeStep,
    nextStep
});

// Substitui a função adicionarItem para devolver o foco ao campo de itens
function adicionarItemComFoco() {
    adicionarItem();
    focusItensServico();
}

</script>

<style scoped>
/* Força o campo AutoComplete de cliente a ocupar toda a largura disponível */
.cliente-autocomplete :deep(.p-autocomplete),
.nome-servico-autocomplete :deep(.p-autocomplete),
.itens-servico-autocomplete :deep(.p-autocomplete) {
    width: 100% !important;
    min-width: 0 !important;
}

.cliente-autocomplete :deep(.p-autocomplete .p-inputtext),
.nome-servico-autocomplete :deep(.p-autocomplete .p-inputtext),
.itens-servico-autocomplete :deep(.p-autocomplete .p-inputtext) {
    width: 100% !important;
    min-width: 0 !important;
}

.cliente-autocomplete :deep(.p-autocomplete-input),
.nome-servico-autocomplete :deep(.p-autocomplete-input),
.itens-servico-autocomplete :deep(.p-autocomplete-input) {
    width: 100% !important;
    min-width: 0 !important;
}
</style>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="isEdit ? 'Editar Orçamento' : 'Novo Orçamento'" />
        <Toast position="top-center" />
        <div class="flex flex-col min-h-[calc(100vh-64px)] w-full bg-background p-0">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 px-8 pt-8 pb-4">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" as-child>
                        <router-link to="/orcamentos">
                            <X class="h-4 w-4" />
                        </router-link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-[#3B82F6] dark:text-[#60A5FA] flex items-center gap-2">
                            <Package class="h-8 w-8" />
                            {{ isEdit ? 'Editar Orçamento' : 'Novo Orçamento' }}
                        </h1>
                        <p class="text-[#64748B] dark:text-[#CBD5E1]">Preencha os dados do orçamento abaixo</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 px-8 pb-8">
                <Card class="p-8">
                    <CardHeader>
                        <CardTitle>{{ isEdit ? 'Editar Orçamento' : 'Novo Orçamento' }}</CardTitle>
                        <CardDescription>Utilize o passo a passo para criar o orçamento de forma fácil e organizada.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="w-full overflow-x-auto mb-8">
                            <Steps :model="steps" :activeIndex="Number(activeStep)" class="w-full min-w-[320px]" />
                        </div>
                        <div v-if="activeStep === 0">
                            <form @submit.prevent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <Label for="cliente">Cliente *</Label>
                                            <AutoComplete
                                                id="cliente"
                                                ref="clienteInputRef"
                                                v-model="formData.cliente_id"
                                                :suggestions="clientesSugestoes"
                                                @complete="buscarClientes"
                                                :loading="clienteCarregando"
                                                optionLabel="nome"
                                                :optionValue="(cliente: Cliente) => cliente.id"
                                                placeholder="Digite para buscar clientes..."
                                                class="w-full cliente-autocomplete"
                                                :class="{ 'p-invalid': form.errors.cliente_id }"
                                                complete-on-focus
                                                @keydown.enter.prevent="focusNext('statusInputRef')"
                                            >
                                                <template #option="{ option }">
                                                    <div class="flex flex-col">
                                                        <span class="font-medium">{{ option.nome }}</span>
                                                        <span v-if="option.documento" class="text-sm text-gray-500">{{ option.documento }}</span>
                                                    </div>
                                                </template>
                                            </AutoComplete>
                                            <small v-if="form.errors.cliente_id" class="text-red-500">{{ form.errors.cliente_id }}</small>
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="status">Status</Label>
                                            <Dropdown
                                                id="status"
                                                ref="statusInputRef"
                                                v-model="formData.status"
                                                :options="statusOptions"
                                                optionLabel="label"
                                                optionValue="value"
                                                placeholder="Selecione o status"
                                                class="w-full"
                                                :class="{ 'p-invalid': form.errors.status }"
                                                @keydown.enter.prevent="focusNext('validadeInputRef')"
                                            />
                                            <small v-if="form.errors.status" class="text-red-500">{{ form.errors.status }}</small>
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="validade">Validade</Label>
                                            <Calendar
                                                id="validade"
                                                ref="validadeInputRef"
                                                v-model="formData.validade"
                                                dateFormat="dd/mm/yy"
                                                placeholder="dd/mm/aaaa"
                                                class="w-full"
                                                :class="{ 'p-invalid': form.errors.validade }"
                                                showIcon
                                                @keydown.enter.prevent="focusNext('observacoesInputRef')"
                                            />
                                            <small v-if="form.errors.validade" class="text-red-500">{{ form.errors.validade }}</small>
                                        </div>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <Label for="observacoes">Observações</Label>
                                            <Textarea
                                                id="observacoes"
                                                ref="observacoesInputRef"
                                                v-model="formData.observacoes"
                                                placeholder="Observações adicionais..."
                                                rows="8"
                                                class="w-full min-h-[90px]"
                                                :class="{ 'p-invalid': form.errors.observacoes }"
                                                @keydown.enter.prevent="nextStep()"
                                            />
                                            <small v-if="form.errors.observacoes" class="text-red-500">{{ form.errors.observacoes }}</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div v-else-if="activeStep === 1">
                            <!-- Step 2: Serviços do Orçamento -->
                            <Card>
                                <CardHeader>
                                    <CardTitle>Serviços do Orçamento</CardTitle>
                                    <CardDescription>Adicione os serviços e seus itens detalhados</CardDescription>
                                </CardHeader>
                                <CardContent class="space-y-6">
                                    <!-- Adicionar Novo Serviço -->
                                    <div class="rounded-lg border bg-gray-50 p-6">
                                        <h4 class="mb-4 text-lg font-medium">Adicionar Novo Serviço</h4>
                                        <!-- Linha horizontal: Nome do Serviço, Itens do Serviço, Botão Adicionar -->
                                        <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:gap-4">
                                            <div class="flex-1 min-w-0">
                                                <Label>Nome do Serviço *</Label>
                                                <AutoComplete
                                                    v-model="novoServico.nome"
                                                    :suggestions="servicoSugestoes"
                                                    @complete="buscarServicos"
                                                    :loading="servicoCarregando"
                                                    optionLabel="nome"
                                                    optionValue="nome"
                                                    placeholder="Digite para buscar serviços..."
                                                    class="w-full nome-servico-autocomplete"
                                                    complete-on-focus
                                                    ref="nomeServicoRef"
                                                    @item-select="focusItensServico"
                                                    @keydown.enter.prevent="focusItensServico()"
                                                >
                                                    <template #option="{ option }">
                                                        <div class="flex flex-col">
                                                            <span class="font-medium">{{ option.nome }}</span>
                                                        </div>
                                                    </template>
                                                </AutoComplete>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <Label>Itens do Serviço</Label>
                                                <AutoComplete
                                                    v-model="novoItem"
                                                    :suggestions="itensSugestoes"
                                                    @complete="buscarItensServico"
                                                    :loading="itensCarregando"
                                                    optionLabel="nome"
                                                    optionValue="nome"
                                                    placeholder="Buscar item do serviço..."
                                                    class="w-full itens-servico-autocomplete"
                                                    complete-on-focus
                                                    ref="itensServicoRef"
                                                    @item-select="focusBtnAdicionar"
                                                    @keydown.enter.prevent="focusBtnAdicionar()"
                                                >
                                                    <template #option="{ option }">
                                                        <div class="flex flex-col">
                                                            <span class="font-medium">{{ option.nome }}</span>
                                                        </div>
                                                    </template>
                                                </AutoComplete>
                                            </div>
                                            <div class="flex-shrink-0 w-auto">
                                                <ButtonPrime @click="adicionarItemComFoco" icon="pi pi-plus" label="Adicionar" size="small" type="button" ref="btnAdicionarRef" @keydown.enter.prevent="adicionarItemComFoco()" />
                                            </div>
                                        </div>
                                        <!-- Lista de itens adicionados -->
                                        <div v-if="novoServico.itens.length > 0" class="space-y-2">
                                            <p class="text-sm font-medium text-gray-700">Itens adicionados:</p>
                                            <div class="space-y-2">
                                                <div
                                                    v-for="(item, index) in novoServico.itens"
                                                    :key="index"
                                                    class="flex items-center justify-between rounded-md border bg-white p-3"
                                                >
                                                    <span class="flex-1">• {{ item }}</span>
                                                    <ButtonPrime
                                                        @click="removerItem(index)"
                                                        icon="pi pi-trash"
                                                        size="small"
                                                        severity="danger"
                                                        text
                                                        type="button"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Valor e Observações -->
                                        <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div class="space-y-2">
                                                <Label>Valor Total do Serviço *</Label>
                                                <InputNumber
                                                    v-model="novoServico.valor_total"
                                                    mode="currency"
                                                    currency="BRL"
                                                    locale="pt-BR"
                                                    :min="0"
                                                    class="w-full"
                                                />
                                            </div>
                                            <div class="space-y-2">
                                                <Label>Observações (opcional)</Label>
                                                <InputText v-model="novoServico.observacoes" placeholder="Observações específicas deste serviço" class="w-full" />
                                            </div>
                                        </div>
                                        <!-- Botão para adicionar serviço -->
                                        <div class="text-right">
                                            <ButtonPrime @click="adicionarServico" icon="pi pi-check" label="Adicionar Serviço" type="button" />
                                        </div>
                                    </div>
                                    <!-- Lista de Serviços Adicionados -->
                                    <div v-if="servicos.length > 0">
                                        <h4 class="mb-4 text-lg font-medium">Serviços Adicionados</h4>
                                        <div class="space-y-4">
                                            <Card v-for="(servico, index) in servicos" :key="index" class="border">
                                                <CardHeader class="pb-3">
                                                    <div class="flex items-center justify-between">
                                                        <CardTitle class="text-lg">{{ servico.nome }}</CardTitle>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-green-600">{{ formatCurrency(servico.valor_total) }}</span>
                                                            <ButtonPrime
                                                                @click="removerServico(index)"
                                                                icon="pi pi-trash"
                                                                size="small"
                                                                severity="danger"
                                                                text
                                                                type="button"
                                                            />
                                                        </div>
                                                    </div>
                                                </CardHeader>
                                                <CardContent>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="mb-2 text-sm font-medium text-gray-700">Itens:</p>
                                                            <ul class="space-y-1">
                                                                <li v-for="item in servico.itens" :key="item" class="text-sm text-gray-600">• {{ item }}</li>
                                                            </ul>
                                                        </div>
                                                        <div v-if="servico.observacoes">
                                                            <p class="text-sm font-medium text-gray-700">Observações:</p>
                                                            <p class="text-sm text-gray-600">{{ servico.observacoes }}</p>
                                                        </div>
                                                    </div>
                                                </CardContent>
                                            </Card>
                                        </div>
                                    </div>
                                    <div v-else class="py-8 text-center text-gray-500">Nenhum serviço adicionado ainda</div>
                                </CardContent>
                            </Card>
                        </div>
                        <div v-else-if="activeStep === 2">
                            <!-- Step 3: Totais e Finalização -->
                            <Card>
                                <CardHeader>
                                    <CardTitle>Totais e Finalização</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                        <!-- Desconto -->
                                        <div class="space-y-2">
                                            <Label for="desconto">Desconto</Label>
                                            <InputNumber
                                                id="desconto"
                                                v-model="formData.desconto"
                                                mode="currency"
                                                currency="BRL"
                                                locale="pt-BR"
                                                :min="0"
                                                class="w-full"
                                                :class="{ 'p-invalid': form.errors.desconto }"
                                            />
                                            <small v-if="form.errors.desconto" class="text-red-500">{{ form.errors.desconto }}</small>
                                        </div>
                                        <!-- Impostos -->
                                        <div class="space-y-2">
                                            <Label for="impostos">Impostos</Label>
                                            <InputNumber
                                                id="impostos"
                                                v-model="formData.impostos"
                                                mode="currency"
                                                currency="BRL"
                                                locale="pt-BR"
                                                :min="0"
                                                class="w-full"
                                                :class="{ 'p-invalid': form.errors.impostos }"
                                            />
                                            <small v-if="form.errors.impostos" class="text-red-500">{{ form.errors.impostos }}</small>
                                        </div>
                                        <!-- Total Final -->
                                        <div class="space-y-2">
                                            <Label>Total Final</Label>
                                            <div class="rounded-md border border-blue-200 bg-blue-50 p-3">
                                                <span class="text-lg font-bold text-blue-900">{{ formatCurrency(total) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 rounded-lg bg-gray-50 p-4">
                                        <div class="space-y-1 text-sm">
                                            <div class="flex justify-between">
                                                <span>Subtotal:</span>
                                                <span>{{ formatCurrency(subtotal) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Desconto:</span>
                                                <span>- {{ formatCurrency(formData.desconto) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Impostos:</span>
                                                <span>+ {{ formatCurrency(formData.impostos) }}</span>
                                            </div>
                                            <hr class="my-2" />
                                            <div class="flex justify-between text-lg font-bold">
                                                <span>Total:</span>
                                                <span>{{ formatCurrency(total) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        <div class="flex items-center gap-4 justify-end mt-8">
                            <ButtonPrime v-if="activeStep > 0" @click="prevStep" label="Voltar" icon="pi pi-arrow-left" class="p-button-outlined px-8 py-3 text-lg" />
                            <ButtonPrime v-if="activeStep < steps.length - 1" @click="nextStep" label="Avançar" iconPos="right" icon="pi pi-arrow-right" class="p-button-primary ml-auto px-8 py-3 text-lg" />
                            <ButtonPrime v-if="activeStep === steps.length - 1" @click="submit" label="Salvar Orçamento" icon="pi pi-check" class="p-button-success ml-auto px-8 py-3 text-lg" />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.cliente-autocomplete :deep(.p-autocomplete),
.nome-servico-autocomplete :deep(.p-autocomplete),
.itens-servico-autocomplete :deep(.p-autocomplete) {
    width: 100% !important;
    min-width: 0 !important;
}

.cliente-autocomplete :deep(.p-autocomplete .p-inputtext),
.nome-servico-autocomplete :deep(.p-autocomplete .p-inputtext),
.itens-servico-autocomplete :deep(.p-autocomplete .p-inputtext) {
    width: 100% !important;
    min-width: 0 !important;
}

.cliente-autocomplete :deep(.p-autocomplete-input),
.nome-servico-autocomplete :deep(.p-autocomplete-input),
.itens-servico-autocomplete :deep(.p-autocomplete-input) {
    width: 100% !important;
    min-width: 0 !important;
}
</style>
