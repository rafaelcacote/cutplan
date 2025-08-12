<?php

require_once 'vendor/autoload.php';

// Carregar as configurações do Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "=== INTROSPECÇÃO DO BANCO DE DADOS CUTPLAN ===\n\n";

    // Obter lista de tabelas
    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::connection()->getDatabaseName();

    echo "Banco de dados: $databaseName\n";
    echo "Total de tabelas: " . count($tables) . "\n\n";

    $documentation = "# Documentação do Banco de Dados - CutPlan\n\n";
    $documentation .= "**Data da geração:** " . date('d/m/Y H:i:s') . "\n";
    $documentation .= "**Banco de dados:** $databaseName\n";
    $documentation .= "**Total de tabelas:** " . count($tables) . "\n\n";

    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];

        echo "Processando tabela: $tableName\n";

        $documentation .= "## Tabela: `$tableName`\n\n";

        // Obter estrutura da tabela
        $columns = DB::select("DESCRIBE $tableName");

        $documentation .= "### Colunas\n\n";
        $documentation .= "| Coluna | Tipo | Null | Chave | Padrão | Extra |\n";
        $documentation .= "|--------|------|------|-------|--------|-------|\n";

        foreach ($columns as $column) {
            $documentation .= "| `{$column->Field}` | {$column->Type} | {$column->Null} | {$column->Key} | {$column->Default} | {$column->Extra} |\n";
        }

        $documentation .= "\n";

        // Obter chaves estrangeiras
        $foreignKeys = DB::select("
            SELECT
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME,
                CONSTRAINT_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE
                TABLE_SCHEMA = '$databaseName'
                AND TABLE_NAME = '$tableName'
                AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($foreignKeys)) {
            $documentation .= "### Chaves Estrangeiras\n\n";
            $documentation .= "| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |\n";
            $documentation .= "|--------------|---------------------|---------------------|--------------------|\n";

            foreach ($foreignKeys as $fk) {
                $documentation .= "| `{$fk->COLUMN_NAME}` | `{$fk->REFERENCED_TABLE_NAME}` | `{$fk->REFERENCED_COLUMN_NAME}` | {$fk->CONSTRAINT_NAME} |\n";
            }
            $documentation .= "\n";
        }

        // Obter índices
        $indexes = DB::select("SHOW INDEX FROM $tableName");

        if (!empty($indexes)) {
            $documentation .= "### Índices\n\n";
            $documentation .= "| Nome do Índice | Coluna | Único | Tipo |\n";
            $documentation .= "|----------------|--------|-------|------|\n";

            foreach ($indexes as $index) {
                $unique = $index->Non_unique == 0 ? 'Sim' : 'Não';
                $documentation .= "| `{$index->Key_name}` | `{$index->Column_name}` | $unique | {$index->Index_type} |\n";
            }
            $documentation .= "\n";
        }

        $documentation .= "---\n\n";
    }

    // Salvar documentação
    file_put_contents('database_documentation.md', $documentation);

    echo "\nDocumentação gerada com sucesso em: database_documentation.md\n";
    echo "Total de tabelas processadas: " . count($tables) . "\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
