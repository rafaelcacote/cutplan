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
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dropdown from 'primevue/dropdown';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { computed, ref, watch } from 'vue';

interface Cliente {
    id: number;
    nome: string;
    documento?: string;
    email?: string;
}

interface Servico {
    id: number;
    nome: string;
    descricao?: string;
    preco_base?: string;
}

interface ServicoItem {
    id: number;
    descricao_item: string;
}

interface Unidade {
    id: number;
    codigo: string;
    nome: string;
}

interface ItemOrcamento {
    id?: number;
    servico_id?: number;
    servicos_itens_id?: number;
    descricao: string;
    quantidade: number;
    unidade_id?: number;
    preco_unitario: number;
    total: number;
    eh_servico: boolean;
    // Para o componente
    servico?: Servico;
    servico_item?: ServicoItem;
    unidade?: Unidade;
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
        itens: ItemOrcamento[];
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
const form = useForm({
    cliente_id: props.orcamento?.cliente_id || null,
    status: props.orcamento?.status || 'draft',
    moeda: props.orcamento?.moeda || 'BRL',
    desconto: parseFloat(props.orcamento?.desconto || '0'),
    impostos: parseFloat(props.orcamento?.impostos || '0'),
    validade: props.orcamento?.validade ? new Date(props.orcamento.validade) : null,
    observacoes: props.orcamento?.observacoes || '',
    itens: props.orcamento?.itens || [],
});

// Estados para autocomplete
const clienteSelecionado = ref<Cliente | null>(props.orcamento?.cliente || null);
const clientesSugestoes = ref<Cliente[]>([]);
const clienteCarregando = ref(false);

// Estados para itens
const itens = ref<ItemOrcamento[]>(
    props.orcamento?.itens.map((item) => ({
        ...item,
        quantidade: parseFloat(item.quantidade.toString()),
        preco_unitario: parseFloat(item.preco_unitario.toString()),
        total: parseFloat(item.total.toString()),
    })) || [],
);

// Novo item
const novoItem = ref<ItemOrcamento>({
    descricao: '',
    quantidade: 1,
    preco_unitario: 0,
    total: 0,
    eh_servico: false,
});

// Estados para autocomplete dos itens
const servicoSugestoes = ref<Servico[]>([]);
const servicoItemSugestoes = ref<ServicoItem[]>([]);
const unidadeSugestoes = ref<Unidade[]>([]);

const statusOptions = [
    { label: 'Rascunho', value: 'draft' },
    { label: 'Enviado', value: 'sent' },
    { label: 'Aprovado', value: 'approved' },
    { label: 'Rejeitado', value: 'rejected' },
    { label: 'Expirado', value: 'expired' },
];

const moedaOptions = [
    { label: 'Real (BRL)', value: 'BRL' },
    { label: 'Dólar (USD)', value: 'USD' },
    { label: 'Euro (EUR)', value: 'EUR' },
];

const toast = useToast();

// Funções de autocomplete
const buscarClientes = async (event: any) => {
    clienteCarregando.value = true;
    try {
        const response = await fetch(`/autocomplete/clientes?search=${encodeURIComponent(event.query)}`);
        clientesSugestoes.value = await response.json();
    } catch (error) {
        console.error('Erro ao buscar clientes:', error);
    } finally {
        clienteCarregando.value = false;
    }
};

const buscarServicos = async (event: any) => {
    try {
        const response = await fetch(`/autocomplete/servicos?search=${encodeURIComponent(event.query)}`);
        servicoSugestoes.value = await response.json();
    } catch (error) {
        console.error('Erro ao buscar serviços:', error);
    }
};

const buscarServicoItens = async (event: any) => {
    try {
        const response = await fetch(`/autocomplete/servico-itens?search=${encodeURIComponent(event.query)}`);
        servicoItemSugestoes.value = await response.json();
    } catch (error) {
        console.error('Erro ao buscar itens de serviço:', error);
    }
};

const buscarUnidades = async (event: any) => {
    try {
        const response = await fetch(`/autocomplete/unidades?search=${encodeURIComponent(event.query)}`);
        unidadeSugestoes.value = await response.json();
    } catch (error) {
        console.error('Erro ao buscar unidades:', error);
    }
};

// Funções dos itens
const calcularTotalItem = (item: ItemOrcamento) => {
    item.total = item.quantidade * item.preco_unitario;
};

const adicionarItem = () => {
    if (!novoItem.value.descricao || novoItem.value.quantidade <= 0 || novoItem.value.preco_unitario < 0) {
        toast.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Preencha todos os campos obrigatórios do item.',
            life: 3000,
        });
        return;
    }

    calcularTotalItem(novoItem.value);
    itens.value.push({ ...novoItem.value });

    // Reset novo item
    novoItem.value = {
        descricao: '',
        quantidade: 1,
        preco_unitario: 0,
        total: 0,
        eh_servico: false,
    };
};

const removerItem = (index: number) => {
    itens.value.splice(index, 1);
};

// Calculados
const subtotal = computed(() => {
    return itens.value.reduce((acc, item) => acc + item.total, 0);
});

const total = computed(() => {
    return subtotal.value - form.desconto + form.impostos;
});

// Funções de formulário
const onClienteSelect = (cliente: Cliente) => {
    form.cliente_id = cliente.id;
    clienteSelecionado.value = cliente;
};

const onServicoSelect = (servico: Servico) => {
    novoItem.value.servico_id = servico.id;
    novoItem.value.servico = servico;
    if (servico.preco_base) {
        novoItem.value.preco_unitario = parseFloat(servico.preco_base);
        calcularTotalItem(novoItem.value);
    }
    if (servico.nome && !novoItem.value.descricao) {
        novoItem.value.descricao = servico.nome;
    }
    novoItem.value.eh_servico = true;
};

const onServicoItemSelect = (servicoItem: ServicoItem) => {
    novoItem.value.servicos_itens_id = servicoItem.id;
    novoItem.value.servico_item = servicoItem;
    if (servicoItem.descricao_item && !novoItem.value.descricao) {
        novoItem.value.descricao = servicoItem.descricao_item;
    }
};

const onUnidadeSelect = (unidade: Unidade) => {
    novoItem.value.unidade_id = unidade.id;
    novoItem.value.unidade = unidade;
};

const submit = () => {
    form.itens = itens.value;

    if (isEdit.value) {
        form.put(`/orcamentos/${props.orcamento!.id}`, {
            preserveScroll: true,
        });
    } else {
        form.post('/orcamentos', {
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

// Watchers para recalcular totais
watch(
    () => novoItem.value.quantidade,
    () => calcularTotalItem(novoItem.value),
);
watch(
    () => novoItem.value.preco_unitario,
    () => calcularTotalItem(novoItem.value),
);

itens.value.forEach((item, index) => {
    watch(
        () => item.quantidade,
        () => calcularTotalItem(item),
    );
    watch(
        () => item.preco_unitario,
        () => calcularTotalItem(item),
    );
});
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
                                    v-model="form.cliente_id"
                                    :suggestions="clientesSugestoes"
                                    @complete="buscarClientes"
                                    :loading="clienteCarregando"
                                    optionLabel="nome"
                                    optionValue="id"
                                    placeholder="Digite para buscar clientes..."
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.cliente_id }"
                                    complete-on-focus
                                    force-selection
                                >
                                    <template #option="{ option }">
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ option.nome }}</span>
                                            <span v-if="option.documento" class="text-sm text-gray-500">{{ option.documento }}</span>
                                        </div>
                                    </template>
                                    <template #value="{ value }">
                                        <span v-if="value">
                                            {{ (clientesSugestoes.find((c) => c.id === value) || props.orcamento?.cliente)?.nome || 'Selecione...' }}
                                        </span>
                                        <span v-else class="text-gray-400">Selecione...</span>
                                    </template>
                                </AutoComplete>
                                <small v-if="form.errors.cliente_id" class="text-red-500">{{ form.errors.cliente_id }}</small>
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <Label for="status">Status</Label>
                                <Dropdown
                                    id="status"
                                    v-model="form.status"
                                    :options="statusOptions"
                                    option-label="label"
                                    option-value="value"
                                    placeholder="Selecione o status"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.status }"
                                />
                                <small v-if="form.errors.status" class="text-red-500">{{ form.errors.status }}</small>
                            </div>

                            <!-- Moeda -->
                            <div class="space-y-2">
                                <Label for="moeda">Moeda</Label>
                                <Dropdown
                                    id="moeda"
                                    v-model="form.moeda"
                                    :options="moedaOptions"
                                    option-label="label"
                                    option-value="value"
                                    placeholder="Selecione a moeda"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.moeda }"
                                />
                                <small v-if="form.errors.moeda" class="text-red-500">{{ form.errors.moeda }}</small>
                            </div>

                            <!-- Validade -->
                            <div class="space-y-2">
                                <Label for="validade">Data de Validade</Label>
                                <Calendar
                                    id="validade"
                                    v-model="form.validade"
                                    date-format="dd/mm/yy"
                                    placeholder="dd/mm/aaaa"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.validade }"
                                    show-icon
                                />
                                <small v-if="form.errors.validade" class="text-red-500">{{ form.errors.validade }}</small>
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="space-y-2">
                            <Label for="observacoes">Observações</Label>
                            <Textarea
                                id="observacoes"
                                v-model="form.observacoes"
                                placeholder="Observações adicionais..."
                                rows="3"
                                class="w-full"
                                :class="{ 'p-invalid': form.errors.observacoes }"
                            />
                            <small v-if="form.errors.observacoes" class="text-red-500">{{ form.errors.observacoes }}</small>
                        </div>
                    </CardContent>
                </Card>

                <!-- Itens do Orçamento -->
                <Card>
                    <CardHeader>
                        <CardTitle>Itens do Orçamento</CardTitle>
                        <CardDescription>Adicione os itens e serviços do orçamento</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Adicionar Novo Item -->
                        <div class="rounded-lg border bg-gray-50 p-4">
                            <h4 class="mb-4 font-medium">Adicionar Item</h4>

                            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <!-- Serviço -->
                                <div class="space-y-2">
                                    <Label>Serviço (opcional)</Label>
                                    <AutoComplete
                                        v-model="novoItem.servico_id"
                                        :suggestions="servicoSugestoes"
                                        @complete="buscarServicos"
                                        optionLabel="nome"
                                        optionValue="id"
                                        placeholder="Digite para buscar serviços..."
                                        class="w-full"
                                        complete-on-focus
                                    >
                                        <template #option="{ option }">
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ option.nome }}</span>
                                                <span v-if="option.descricao" class="text-sm text-gray-500">{{ option.descricao }}</span>
                                            </div>
                                        </template>
                                        <template #value="{ value }">
                                            <span v-if="value">
                                                {{ servicoSugestoes.find((s) => s.id === value)?.nome || 'Selecione...' }}
                                            </span>
                                            <span v-else class="text-gray-400">Selecione...</span>
                                        </template>
                                    </AutoComplete>
                                </div>

                                <!-- Item de Serviço -->
                                <div class="space-y-2">
                                    <Label>Item de Serviço (opcional)</Label>
                                    <AutoComplete
                                        v-model="novoItem.servicos_itens_id"
                                        :suggestions="servicoItemSugestoes"
                                        @complete="buscarServicoItens"
                                        optionLabel="descricao_item"
                                        optionValue="id"
                                        placeholder="Digite para buscar itens..."
                                        class="w-full"
                                        complete-on-focus
                                    >
                                        <template #option="{ option }">
                                            <span>{{ option.descricao_item }}</span>
                                        </template>
                                        <template #value="{ value }">
                                            <span v-if="value">
                                                {{ servicoItemSugestoes.find((i) => i.id === value)?.descricao_item || 'Selecione...' }}
                                            </span>
                                            <span v-else class="text-gray-400">Selecione...</span>
                                        </template>
                                    </AutoComplete>
                                </div>

                                <!-- Unidade -->
                                <div class="space-y-2">
                                    <Label>Unidade (opcional)</Label>
                                    <AutoComplete
                                        v-model="novoItem.unidade_id"
                                        :suggestions="unidadeSugestoes"
                                        @complete="buscarUnidades"
                                        optionLabel="nome"
                                        optionValue="id"
                                        placeholder="Digite para buscar unidades..."
                                        class="w-full"
                                        complete-on-focus
                                    >
                                        <template #option="{ option }">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium">{{ option.codigo }}</span>
                                                <span class="text-sm text-gray-500">{{ option.nome }}</span>
                                            </div>
                                        </template>
                                        <template #value="{ value }">
                                            <span v-if="value">
                                                {{ unidadeSugestoes.find((u) => u.id === value)?.nome || 'Selecione...' }}
                                            </span>
                                            <span v-else class="text-gray-400">Selecione...</span>
                                        </template>
                                    </AutoComplete>
                                </div>
                            </div>

                            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-4">
                                <!-- Descrição -->
                                <div class="space-y-2">
                                    <Label>Descrição *</Label>
                                    <InputText v-model="novoItem.descricao" placeholder="Descrição do item" class="w-full" />
                                </div>

                                <!-- Quantidade -->
                                <div class="space-y-2">
                                    <Label>Quantidade *</Label>
                                    <InputNumber v-model="novoItem.quantidade" :min="0.0001" :step="0.01" :max-fraction-digits="4" class="w-full" />
                                </div>

                                <!-- Preço Unitário -->
                                <div class="space-y-2">
                                    <Label>Preço Unitário *</Label>
                                    <InputNumber
                                        v-model="novoItem.preco_unitario"
                                        mode="currency"
                                        currency="BRL"
                                        locale="pt-BR"
                                        :min="0"
                                        class="w-full"
                                    />
                                </div>

                                <!-- Total -->
                                <div class="space-y-2">
                                    <Label>Total</Label>
                                    <InputText :value="formatCurrency(novoItem.total)" readonly class="w-full bg-gray-100" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <Checkbox v-model="novoItem.eh_servico" binary input-id="eh-servico" />
                                    <Label for="eh-servico">É um serviço</Label>
                                </div>

                                <ButtonPrime @click="adicionarItem" icon="pi pi-plus" label="Adicionar Item" size="small" />
                            </div>
                        </div>

                        <!-- Lista de Itens -->
                        <div v-if="itens.length > 0">
                            <DataTable :value="itens" responsive-layout="scroll" class="p-datatable-sm">
                                <Column field="descricao" header="Descrição">
                                    <template #body="{ data, index }">
                                        <InputText v-model="data.descricao" class="w-full border-0 p-1" />
                                    </template>
                                </Column>

                                <Column field="quantidade" header="Qtd" class="w-24">
                                    <template #body="{ data }">
                                        <InputNumber
                                            v-model="data.quantidade"
                                            :min="0.0001"
                                            :step="0.01"
                                            :max-fraction-digits="4"
                                            class="w-full border-0"
                                            size="small"
                                        />
                                    </template>
                                </Column>

                                <Column field="preco_unitario" header="Preço Unit." class="w-32">
                                    <template #body="{ data }">
                                        <InputNumber
                                            v-model="data.preco_unitario"
                                            mode="currency"
                                            currency="BRL"
                                            locale="pt-BR"
                                            :min="0"
                                            class="w-full border-0"
                                            size="small"
                                        />
                                    </template>
                                </Column>

                                <Column field="total" header="Total" class="w-32">
                                    <template #body="{ data }">
                                        <span class="font-medium">{{ formatCurrency(data.total) }}</span>
                                    </template>
                                </Column>

                                <Column header="Ações" class="w-16">
                                    <template #body="{ index }">
                                        <ButtonPrime @click="removerItem(index)" icon="pi pi-trash" size="small" severity="danger" text />
                                    </template>
                                </Column>
                            </DataTable>
                        </div>

                        <div v-else class="py-8 text-center text-gray-500">Nenhum item adicionado ainda</div>
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
                                    v-model="form.desconto"
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
                                    v-model="form.impostos"
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
                                    <span>- {{ formatCurrency(form.desconto) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Impostos:</span>
                                    <span>+ {{ formatCurrency(form.impostos) }}</span>
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
