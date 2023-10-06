<?php

namespace Tests\Feature;

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    private const MOCKED_ACCOUNT_ID_1 = 100;
    private const MOCKED_ACCOUNT_ID_2 = 200;
    private const MOCKED_ACCOUNT_ID_3 = 300;

    public function test_it_resets_the_cache()
    {
        $response = $this->post('/reset');
        $response->assertStatus(200);

        $this->assertEquals($response->getContent(), AccountController::RESET_RESPONSE_OK);
    }

    public function test_it_gets_balance_for_non_existing_account()
    {
        $response = $this->get('/balance?account_id=1234');
        $response->assertStatus(404);

        $this->assertEquals($response->getContent(), 0);
    }

    public function test_it_creates_account_with_initial_balance()
    {
        $response = $this->post('/event', [
            'type' => 'deposit',
            'destination' => self::MOCKED_ACCOUNT_ID_1,
            'amount' => 10,
        ]);

        $response->assertStatus(201);

        $this->assertEquals($response->getOriginalContent(), [
            'destination' => [
                'id' => self::MOCKED_ACCOUNT_ID_1,
                'balance' => 10,
            ]
        ]);
    }

    public function test_it_deposits_into_existing_account()
    {
        $this->mockAccountAmount(10);

        $response = $this->post('/event', [
            'type' => 'deposit',
            'destination' => self::MOCKED_ACCOUNT_ID_1,
            'amount' => 10,
        ]);

        $response->assertStatus(201);

        $this->assertEquals($response->getOriginalContent(), [
            'destination' => [
                'id' => self::MOCKED_ACCOUNT_ID_1,
                'balance' => 20,
            ]
        ]);
    }

    public function test_it_gets_balance_for_existing_account()
    {
        $this->mockAccountAmount(20);

        $response = $this->get('/balance?account_id=100');

        $response->assertStatus(200);

        $this->assertEquals($response->getOriginalContent(), 20);
    }

    public function test_it_withdraws_from_non_existing_account()
    {
        $response = $this->post('/event', [
            'type' => 'withdraw',
            'origin' => self::MOCKED_ACCOUNT_ID_2,
            'amount' => 10,
        ]);

        $response->assertStatus(404);
        $this->assertEquals($response->getOriginalContent(), 0);
    }

    public function test_it_withdraws_from_existing_account()
    {
        $this->mockAccountAmount(20);

        $response = $this->post('/event', [
            'type' => 'withdraw',
            'origin' => self::MOCKED_ACCOUNT_ID_1,
            'amount' => 5,
        ]);

        $response->assertStatus(201);

        $this->assertEquals($response->getOriginalContent(), [
            'origin' => [
                'id' => self::MOCKED_ACCOUNT_ID_1,
                'balance' => 15,
            ]
        ]);
    }

    public function test_it_transfers_from_existing_account()
    {
        $this->mockAccountAmount(15);

        $response = $this->post('/event', [
            'type' => 'transfer',
            'origin' => self::MOCKED_ACCOUNT_ID_1,
            'amount' => 15,
            'destination' => self::MOCKED_ACCOUNT_ID_3,
        ]);

        $response->assertStatus(201);

        $this->assertEquals($response->getOriginalContent(), [
            'origin' => [
                'id' => self::MOCKED_ACCOUNT_ID_1,
                'balance' => 0,
            ],
            'destination' => [
                'id' => self::MOCKED_ACCOUNT_ID_3,
                'balance' => 15,
            ]
        ]);
    }

    public function test_it_transfers_from_non_existing_account()
    {
        $response = $this->post('/event', [
            'type' => 'transfer',
            'origin' => self::MOCKED_ACCOUNT_ID_2,
            'amount' => 15,
            'destination' => 300,
        ]);

        $response->assertStatus(404);
        $this->assertEquals($response->getOriginalContent(), 0);
    }

    private function mockAccountAmount(int $value): void
    {
        Cache::put('account_' . self::MOCKED_ACCOUNT_ID_1,  $value);
    }
}
