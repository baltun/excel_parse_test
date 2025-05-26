<?php

namespace Tests\Unit;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UserSeeder::class,
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_users(): void
    {
//        dd(User::all());
        $users = User::all();
        $this->assertNotEmpty($users, 'Users collection should not be empty');
    }
}
