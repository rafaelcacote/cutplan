<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Lista de tabelas do cutplan
$cutplanTables = [
    'almoxarifados', 'anexos', 'apontamentos_tempo', 'aprovacoes', 'ativos_manutencao',
    'cache', 'cache_locks', 'categories', 'clientes', 'contatos', 'contratos',
    'embarques_logistica', 'enderecos', 'equipes', 'estoques_materiais', 'etapas_producao',
    'execucoes_etapa_projeto', 'failed_jobs', 'fornecedores', 'itens_embarque',
    'itens_orcamento', 'itens_ordem_compra', 'itens_recebimento', 'job_batches',
    'jobs', 'logs_manutencao', 'materiais', 'materiais_projeto', 'membros_equipe',
    'metricas_producao', 'migrations', 'movimentacoes_estoque', 'oportunidades',
    'orcamentos', 'ordens_compra', 'ordens_manutencao', 'ordens_servico',
    'password_reset_tokens', 'pendencias', 'projetos', 'recebimentos', 'sessions',
    'tipos_materiais', 'unidades', 'users', 'valores_metrica_etapa'
];

$documentation = "# Documentação do Banco de Dados - CutPlan\n\n";
$documentation .= "Gerado em: " . date('Y-m-d H:i:s') . "\n\n";

foreach ($cutplanTables as $table) {
    try {
        $columns = Schema::getColumnListing($table);

        $documentation .= "## Tabela: `$table`\n\n";
        $documentation .= "| Coluna | Tipo | Nullable | Default |\n";
        $documentation .= "|--------|------|----------|----------|\n";

        foreach ($columns as $column) {
            $type = Schema::getColumnType($table, $column);
            $documentation .= "| $column | $type | - | - |\n";
        }

        $documentation .= "\n";

    } catch (Exception $e) {
        $documentation .= "## Tabela: `$table` - ERRO\n\n";
        $documentation .= "Erro ao acessar tabela: " . $e->getMessage() . "\n\n";
    }
}

file_put_contents('database_introspection.md', $documentation);
echo "Documentação gerada em database_introspection.md\n";
