<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formasPagamento = [
            'Dinheiro',
            'Cartão de Crédito',
            'Cartão de Débito',
            'PIX',
            'Transferência Bancária',
            'Boleto Bancário',
            'Cheque',
            'Vale Alimentação',
            'Vale Refeição',
            'Cartão Presente',
        ];

        foreach ($formasPagamento as $formaPagamentoName) {
            FormaPagamento::firstOrCreate(['name' => $formaPagamentoName]);
        }
    }
}
