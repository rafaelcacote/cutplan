<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Tecnologia',
            'Saúde',
            'Educação',
            'Esportes',
            'Entretenimento',
            'Negócios',
            'Culinária',
            'Viagem',
            'Arte',
            'Ciência',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }
    }
}
