<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\AuthService;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authService;
    protected $authRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepositoryMock = Mockery::mock(AuthRepositoryInterface::class);

        $this->authService = new AuthService($this->authRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_register()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->authRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(new User([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]));

        $user = $this->authService->register($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }
}
