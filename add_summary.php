<?php

require_once 'vendor/autoload.php';

// Carregar as configurações do Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== GERANDO RESUMO E RELACIONAMENTOS ===\n\n";

    // Obter lista de tabelas
    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::connection()->getDatabaseName();

    $summary = "\n## 📊 Resumo do Sistema\n\n";
    $summary .= "### Principais Módulos do Sistema\n\n";

    // Categorizar tabelas por módulo
    $modules = [
        'Gestão de Clientes' => ['clientes', 'enderecos', 'contatos'],
        'Gestão de Fornecedores' => ['fornecedores', 'contatos'],
        'Gestão de Materiais' => ['materiais', 'tipos_materiais', 'unidades'],
        'Gestão de Projetos' => ['projetos', 'etapas_producao', 'execucoes_etapa_projeto', 'materiais_projeto'],
        'Gestão de Estoque' => ['almoxarifados', 'estoques_materiais', 'movimentacoes_estoque'],
        'Compras e Vendas' => ['orcamentos', 'itens_orcamento', 'ordens_compra', 'itens_ordem_compra', 'recebimentos', 'itens_recebimento'],
        'Logística' => ['embarques_logistica', 'itens_embarque'],
        'Manutenção' => ['ativos_manutencao', 'ordens_manutencao', 'logs_manutencao'],
        'Controle de Produção' => ['ordens_servico', 'apontamentos_tempo', 'metricas_producao', 'valores_metrica_etapa'],
        'Recursos Humanos' => ['equipes', 'membros_equipe'],
        'Aprovações e Workflow' => ['aprovacoes', 'pendencias'],
        'Sistema' => ['users', 'anexos', 'contratos', 'oportunidades'],
        'Laravel Framework' => ['cache', 'cache_locks', 'failed_jobs', 'jobs', 'job_batches', 'migrations', 'password_reset_tokens', 'sessions']
    ];

    foreach ($modules as $moduleName => $moduleTables) {
        $summary .= "#### $moduleName\n";
        foreach ($moduleTables as $table) {
            // Verificar se a tabela existe
            $tableExists = false;
            foreach ($tables as $t) {
                if (array_values((array)$t)[0] === $table) {
                    $tableExists = true;
                    break;
                }
            }
            if ($tableExists) {
                $summary .= "- `$table`\n";
            }
        }
        $summary .= "\n";
    }

    // Obter relacionamentos principais
    $summary .= "### 🔗 Principais Relacionamentos\n\n";

    $relationships = DB::select("
        SELECT
            TABLE_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME,
            CONSTRAINT_NAME
        FROM
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            TABLE_SCHEMA = '$databaseName'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ORDER BY TABLE_NAME, COLUMN_NAME
    ");

    $currentTable = '';
    foreach ($relationships as $rel) {
        if ($currentTable !== $rel->TABLE_NAME) {
            if ($currentTable !== '') {
                $summary .= "\n";
            }
            $summary .= "#### Tabela: `{$rel->TABLE_NAME}`\n";
            $currentTable = $rel->TABLE_NAME;
        }
        $summary .= "- `{$rel->COLUMN_NAME}` → `{$rel->REFERENCED_TABLE_NAME}.{$rel->REFERENCED_COLUMN_NAME}`\n";
    }

    // Estatísticas das tabelas
    $summary .= "\n### 📈 Estatísticas das Tabelas\n\n";
    $summary .= "| Tabela | Total de Colunas | Chaves Estrangeiras | Índices |\n";
    $summary .= "|--------|------------------|---------------------|----------|\n";

    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];

        // Contar colunas
        $columns = DB::select("DESCRIBE $tableName");
        $columnCount = count($columns);

        // Contar chaves estrangeiras
        $fkCount = DB::select("
            SELECT COUNT(*) as count
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '$databaseName'
            AND TABLE_NAME = '$tableName'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        $fkCount = $fkCount[0]->count;

        // Contar índices
        $indexes = DB::select("SHOW INDEX FROM $tableName");
        $indexCount = count($indexes);

        $summary .= "| `$tableName` | $columnCount | $fkCount | $indexCount |\n";
    }

    // Ler o arquivo existente
    $existingContent = file_get_contents('database_documentation.md');

    // Adicionar o resumo no início
    $newContent = explode("\n", $existingContent);
    array_splice($newContent, 5, 0, explode("\n", $summary));

    // Salvar o arquivo atualizado
    file_put_contents('database_documentation.md', implode("\n", $newContent));

    echo "Resumo adicionado com sucesso!\n";
    echo "Total de relacionamentos encontrados: " . count($relationships) . "\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
