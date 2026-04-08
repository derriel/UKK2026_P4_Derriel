<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register', function () {
    $userData = [
        'fname' => 'John',
        'lname' => 'Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password123',
    ];

    $response = $this->post('/signup', $userData);

    $response->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
    ]);

    $this->assertAuthenticated();
});