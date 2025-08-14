<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Save, X } from 'lucide-vue-next';
import AutoComplete from 'primevue/autocomplete';
import ButtonPrime from 'primevue/button';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { computed, ref } from 'vue';

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
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="isEdit ? 'Editar Orçamento' : 'Novo Orçamento'" />

        <Toast position="top-center" />

        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    {{ isEdit ? 'Editar Orçamento' : 'Novo Orçamento' }}
                </h1>
                <p class="mt-1 text-gray-600">
                    {{ isEdit ? 'Atualize as informações do orçamento' : 'Crie um novo orçamento para o cliente' }}
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Informações Básicas -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Básicas</CardTitle>
                        <CardDescription>Dados principais do orçamento</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Cliente -->
                            <div class="space-y-2">
                                <Label for="cliente">Cliente *</Label>
                                <AutoComplete
                                    id="cliente"
                                    v-model="formData.cliente_id"
                                    :suggestions="clientesSugestoes"
                                    @complete="buscarClientes"
                                    :loading="clienteCarregando"
                                    optionLabel="nome"
                                    optionValue="id"
                                    placeholder="Digite para buscar clientes..."
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.cliente_id }"
                                    complete-on-focus
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

                            <!-- Status -->
                            <div class="space-y-2">
                                <Label for="status">Status</Label>
                                <Dropdown
                                    id="status"
                                    v-model="formData.status"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Selecione o status"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.status }"
                                />
                                <small v-if="form.errors.status" class="text-red-500">{{ form.errors.status }}</small>
                            </div>

                            <!-- Validade -->
                            <div class="space-y-2">
                                <Label for="validade">Data de Validade</Label>
                                <Calendar
                                    id="validade"
                                    v-model="formData.validade"
                                    dateFormat="dd/mm/yy"
                                    placeholder="dd/mm/aaaa"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.validade }"
                                    showIcon
                                />
                                <small v-if="form.errors.validade" class="text-red-500">{{ form.errors.validade }}</small>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="space-y-2">
                            <Label for="observacoes">Observações</Label>
                            <Textarea
                                id="observacoes"
                                v-model="formData.observacoes"
                                placeholder="Observações adicionais..."
                                rows="3"
                                class="w-full"
                                :class="{ 'p-invalid': form.errors.observacoes }"
                            />
                            <small v-if="form.errors.observacoes" class="text-red-500">{{ form.errors.observacoes }}</small>
                        </div>
                    </CardContent>
                </Card>

                <!-- Serviços do Orçamento -->
                <Card>
                    <CardHeader>
                        <CardTitle>Serviços do Orçamento</CardTitle>
                        <CardDescription>Adicione os serviços e seus itens detalhados</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Adicionar Novo Serviço -->
                        <div class="rounded-lg border bg-gray-50 p-6">
                            <h4 class="mb-4 text-lg font-medium">Adicionar Novo Serviço</h4>

                            <!-- Nome do Serviço (autocomplete) -->
                            <div class="mb-4 space-y-2">
                                <Label>Nome do Serviço *</Label>
                                <AutoComplete
                                    v-model="novoServico.nome"
                                    :suggestions="servicoSugestoes"
                                    @complete="buscarServicos"
                                    :loading="servicoCarregando"
                                    optionLabel="nome"
                                    optionValue="nome"
                                    placeholder="Digite para buscar serviços..."
                                    class="w-full"
                                    complete-on-focus
                                >
                                    <template #option="{ option }">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ option.nome }}</span>
                                        </div>
                                    </template>
                                </AutoComplete>
                            </div>

                            <!-- Adicionar Itens (autocomplete) -->
                            <div class="mb-4 space-y-4">
                                <Label>Itens do Serviço</Label>
                                <div class="flex gap-2">
                                    <AutoComplete
                                        v-model="novoItem"
                                        :suggestions="itensSugestoes"
                                        @complete="buscarItensServico"
                                        :loading="itensCarregando"
                                        optionLabel="nome"
                                        optionValue="nome"
                                        placeholder="Buscar item do serviço..."
                                        class="flex-1"
                                        complete-on-focus
                                    >
                                        <template #option="{ option }">
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ option.nome }}</span>
                                            </div>
                                        </template>
                                    </AutoComplete>
                                    <ButtonPrime @click="adicionarItem" icon="pi pi-plus" label="Adicionar" size="small" type="button" />
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

                <!-- Totais -->
                <Card>
                    <CardHeader>
                        <CardTitle>Totais</CardTitle>
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

                <!-- Ações -->
                <div class="flex items-center justify-end gap-4">
                    <Button type="button" variant="outline" @click="router.visit('/orcamentos')">
                        <X class="mr-2 h-4 w-4" />
                        Cancelar
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        <Save class="mr-2 h-4 w-4" />
                        {{ isEdit ? 'Atualizar' : 'Criar' }} Orçamento
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
