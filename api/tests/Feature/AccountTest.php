<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Account;
use App\Transaction;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function storeData()
    {
        Account::create([
            'name' => 'John',
            'balance' => 100000
        ]);

        Account::create([
            'name' => 'Peter',
            'balance' => 50000
        ]);

        Transaction::create(
            [
                'from' => 1,
                'to' => 2,
                'amount' => 100,
                'details' => 'first transfer'
            ]
        );
    }

    /**
     * test case example.
     */
    public function testRunning()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testGetAccountDetail()
    {
        $this->storeData();

        $response = $this->get('/api/accounts/1');
        $this->assertEquals('200', $response->status());
        $this->assertEquals('John', $response->getData()->data[0]->name);
    }

    public function testAccountDetailNotFound()
    {
        $this->storeData();

        $response = $this->get('/api/accounts/3');
        $this->assertEquals('200', $response->status());
        $this->assertEquals(false, $response->getData()->success);
    }

    public function testGetAccountTransaction()
    {
        $this->storeData();

        $response = $this->get('/api/accounts/1/transactions');
        $response->assertStatus(200);
        $this->assertEquals('first transfer', $response->getData()->data[0]->details);
    }

    public function testAccountTransfer()
    {
        $this->storeData();

        $response = $this->get('/api/accounts/1');
        $token = $response->getData()->token;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->json('POST', '/api/accounts/1/transactions', ['from'=>1,'to'=>2,'amount'=>100,'details'=>'test transfer']);
        $response->assertStatus(200);
        
        $response = $this->get('/api/accounts/1/transactions');
        $this->assertEquals(2, count($response->getData()->data));
        $this->assertEquals('test transfer', $response->getData()->data[1]->details);
        $this->assertEquals(100, $response->getData()->data[1]->amount);

        $response = $this->get('/api/accounts/1');
        $this->assertEquals('200', $response->status());
        $this->assertEquals((100000-100), $response->getData()->data[0]->balance);
        
    }
}
