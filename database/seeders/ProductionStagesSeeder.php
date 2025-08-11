<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionStagesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Etapas de produção
        $stages = [
            ['codigo' => 'CORTE',        'nome' => 'Corte',        'sequencia' => 1],
            ['codigo' => 'FITAMENTO',    'nome' => 'Fitamento',    'sequencia' => 2],
            ['codigo' => 'COLAGEM',      'nome' => 'Colagem/Consumo de Cola', 'sequencia' => 3],
            ['codigo' => 'PRE_MONTAGEM', 'nome' => 'Pré-montagem', 'sequencia' => 4],
            ['codigo' => 'MONTAGEM',     'nome' => 'Montagem',     'sequencia' => 5],
            ['codigo' => 'VISTORIA',     'nome' => 'Vistoria',     'sequencia' => 6],
            ['codigo' => 'FIM_OBRA',     'nome' => 'Fim de Obra',  'sequencia' => 7],
        ];

        foreach ($stages as $s) {
            DB::table('etapas_producao')->updateOrInsert(
                ['codigo' => $s['codigo']],
                ['nome' => $s['nome'], 'sequencia' => $s['sequencia'], 'ativa' => 1]
            );
        }

        // Unidades básicas
        $units = [
            ['codigo' => 'un', 'nome' => 'Unidade', 'precisao' => 0],
            ['codigo' => 'm',  'nome' => 'Metro',   'precisao' => 3],
            ['codigo' => 'ml', 'nome' => 'Mililitro','precisao' => 0],
            ['codigo' => 'kg', 'nome' => 'Quilo',   'precisao' => 3],
            ['codigo' => 'min','nome' => 'Minuto',  'precisao' => 0],
            ['codigo' => 'dia','nome' => 'Diária',  'precisao' => 0],
        ];

        foreach ($units as $u) {
            DB::table('unidades')->updateOrInsert(
                ['codigo' => $u['codigo']],
                ['nome' => $u['nome'], 'precisao' => $u['precisao']]
            );
        }

        // Métricas de produção
        $unitIds = DB::table('unidades')->pluck('id', 'codigo');

        $metrics = [
            ['codigo' => 'qtd_chapas_utilizadas', 'nome' => 'Quantidade de chapas utilizadas', 'unidade' => 'un'],
            ['codigo' => 'metragem_fitamento',    'nome' => 'Metragem de fitamento',           'unidade' => 'm'],
            ['codigo' => 'consumo_cola_ml',       'nome' => 'Consumo de cola (ml)',            'unidade' => 'ml'],
            ['codigo' => 'diarias_montagem',      'nome' => 'Diárias de montagem',             'unidade' => 'dia'],
        ];

        foreach ($metrics as $m) {
            DB::table('metricas_producao')->updateOrInsert(
                ['codigo' => $m['codigo']],
                ['nome' => $m['nome'], 'unidade_id' => $unitIds[$m['unidade']] ?? null, 'descricao' => null]
            );
        }

        // Tipos de materiais
        $types = ['chapa', 'ferragem', 'consumivel', 'EPI', 'ferramenta', 'maquina', 'tinta', 'servico'];
        foreach ($types as $t) {
            DB::table('tipos_materiais')->updateOrInsert(['nome' => $t], ['nome' => $t]);
        }
    }
}