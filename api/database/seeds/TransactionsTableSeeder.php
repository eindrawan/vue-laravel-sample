<?php

use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'from' => 1,
            'to' => 2,
            'details' => 'sample transaction',
            'amount' => 14,
            'updated_at' => DB::raw('NOW()'),
            'created_at' => DB::raw('NOW()')
        ]);

        DB::table('transactions')->insert([
            'from' => 1,
            'to' => 2,
            'details' => 'sample transaction 2',
            'amount' => 24,
            'updated_at' => DB::raw('NOW()'),
            'created_at' => DB::raw('NOW()')
        ]);

        DB::table('transactions')->insert([
            'from' => 2,
            'to' => 1,
            'details' => 'sample transaction 3',
            'amount' => 15,
            'updated_at' => DB::raw('NOW()'),
            'created_at' => DB::raw('NOW()')
        ]);
    }
}
