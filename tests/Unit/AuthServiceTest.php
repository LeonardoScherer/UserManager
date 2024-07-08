<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\AuthService;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
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
            'phone' => '(31) 98765-4321',
            'cpf' => '123.456.789-00',
        ];

        $this->authRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(new User([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'cpf' => $userData['cpf'],
                'password' => Hash::make($userData['password']),
            ]));

        $user = $this->authService->register($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['phone'], $user->phone); // Verifica o número de telefone
        $this->assertEquals($userData['cpf'], $user->cpf);     // Verifica o CPF
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }


    public function test_login_success()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $user = new User([
            'name' => 'Test User',
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);

        $this->authRepositoryMock
            ->shouldReceive('findByCredentials')
            ->once()
            ->with($credentials)
            ->andReturn($user);

        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($user)
            ->andReturn('mocked_token');

        $token = $this->authService->login($credentials);

        $this->assertEquals('mocked_token', $token);
    }

    public function test_login_user_not_found()
    {
        $credentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ];

        $this->authRepositoryMock
            ->shouldReceive('findByCredentials')
            ->once()
            ->with($credentials)
            ->andReturnNull();

        $token = $this->authService->login($credentials);

        $this->assertNull($token);
    }

    public function test_login_jwt_exception()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $user = new User([
            'name' => 'Test User',
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);

        $this->authRepositoryMock
            ->shouldReceive('findByCredentials')
            ->once()
            ->with($credentials)
            ->andReturn($user);

        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($user)
            ->andThrow(new JWTException('JWT exception'));


        $token = $this->authService->login($credentials);

        $this->assertNull($token);
    }
}
