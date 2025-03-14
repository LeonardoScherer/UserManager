<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $userService;
    protected $userRepositoryMock;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $token = auth()->login($this->user);

        $this->withHeaders(['Authorization' => 'Bearer ' . $token]);

        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $this->userService = new UserService($this->userRepositoryMock);
    }

    public function testSoftDeleteUser()
    {
        $user = User::factory()->create();

        $this->userRepositoryMock->expects($this->once())
            ->method('softDelete')
            ->with($user->id)
            ->willReturn(true);

        $result = $this->userService->softDeleteUser($user->id);

        $this->assertTrue($result);
    }

    public function testSoftDeleteUserNotFound()
    {
        $userId = 999;

        $this->userRepositoryMock->expects($this->once())
            ->method('softDelete')
            ->with($userId)
            ->willReturn(false);

        $result = $this->userService->softDeleteUser($userId);

        $this->assertFalse($result);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $userData = [
            'name' => 'Updated Name',
            'email' => 'teste@teste.com',
            'password' => 'aA123456&'
        ];

        $this->userRepositoryMock->expects($this->once())
            ->method('update')
            ->with($user->id)
            ->willReturn(true);

        $result = $this->userService->update($user->id, $userData);

        $this->assertTrue($result);
    }

    public function test_show_user()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
    }
}
