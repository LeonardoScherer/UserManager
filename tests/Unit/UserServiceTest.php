<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $userService;
    protected $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

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
}
