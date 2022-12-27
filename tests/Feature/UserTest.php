<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * test function for post user registration
     */
    public function test_user_registration(){
        $response = $this->postJson(route('user.register'), [
            'name'=>'Admin',
            'email'=>'admin@admin.com',
            'password'=>Hash::make('password')
        ]);
        $response->assertCreated();
        $response->assertJson([
            'success'=>true
        ]);
    }

    /**
     * test function for user login
     */
    public function test_user_login(){
        $response = $this->postJson(route('user.login'), [
           'email'=>'admin@admin.com',
           'password'=>'password'
        ]);
        $response->assertSuccessful();
        $response->assertJson([
            'success'=>true
        ]);
    }
}
