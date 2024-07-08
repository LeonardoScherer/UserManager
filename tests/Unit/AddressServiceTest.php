<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Address;
use Tests\TestCase;
use App\Services\AddressService;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $addressService;
    protected $addressRepositoryMock;
    protected $authRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $token = auth()->login($this->user);

        $this->withHeaders(['Authorization' => 'Bearer ' . $token]);

        $this->addressRepositoryMock = \Mockery::mock(AddressRepositoryInterface::class);
        $this->authRepositoryMock = \Mockery::mock(AuthRepositoryInterface::class);

        $this->addressService = new AddressService($this->addressRepositoryMock, $this->authRepositoryMock);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function test_store_address()
    {
        $data = [
            'user_id' => $this->user->id,
            'street' => 'Rua Principal',
            'number' => '123',
            'neighborhood' => 'Centro',
            'cep' => '12345-678',
        ];

        $this->mock(AddressService::class, function ($mock) use ($data) {
            $mock->shouldReceive('create')
                ->with($data)
                ->andReturn(new Address($data));
        });

        $response = $this->postJson('/api/address', $data);


        $response->assertStatus(201);
    }

    public function test_store_address_failure()
    {
        $invalidData = [
            'user_id' => 999,
            'street' => 'Rua Principal',
            'number' => '123',
            'neighborhood' => 'Centro',
            'cep' => '12345-678',
        ];

        $response = $this->postJson('/api/address', $invalidData);

        $response->assertStatus(422);
    }


    public function test_get_addresses_by_user_id()
    {
        $userId = 1;

        $this->addressRepositoryMock
            ->shouldReceive('findByUserId')
            ->once()
            ->with($userId)
            ->andReturn(collect([
                new Address([
                    'user_id' => $userId,
                    'street' => 'Rua A',
                    'number' => '123',
                    'neighborhood' => 'Bairro X',
                    'complement' => 'Apto 101',
                    'cep' => '12345-678',
                ]),
                new Address([
                    'user_id' => $userId,
                    'street' => 'Rua B',
                    'number' => '456',
                    'neighborhood' => 'Bairro Y',
                    'complement' => null,
                    'cep' => '98765-432',
                ]),
            ]));

        $addresses = $this->addressService->getByUserId($userId);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $addresses);

        $this->assertCount(2, $addresses);

        $this->assertEquals('Rua A', $addresses[0]->street);
        $this->assertEquals('123', $addresses[0]->number);
        $this->assertEquals('Bairro X', $addresses[0]->neighborhood);
        $this->assertEquals('Apto 101', $addresses[0]->complement);
        $this->assertEquals('12345-678', $addresses[0]->cep);

        $this->assertEquals('Rua B', $addresses[1]->street);
        $this->assertEquals('456', $addresses[1]->number);
        $this->assertEquals('Bairro Y', $addresses[1]->neighborhood);
        $this->assertNull($addresses[1]->complement);
        $this->assertEquals('98765-432', $addresses[1]->cep);
    }
}
