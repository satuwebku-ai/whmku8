<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            ProductCatalogSeeder::class,
            CmsPageSeeder::class,
            CmsPostSeeder::class,
            KnowledgeBaseSeeder::class,
        ]);
    }
}
