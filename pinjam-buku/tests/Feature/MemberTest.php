<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_member(): void
    {
        $formData = [
            'name' => 'John Doe',
        ];

        $response = $this->postJson('/member/create', $formData);


        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'code',
            'name',
        ]);
    }

    public function test_get_all(): void
    {
        
        for($i = 0; $i<4; $i++) {
            $formData = [
                'name' => 'John Doe',
            ];
    
            $this->postJson('/member/create', $formData);
        }

        $response = $this->get('/member/getAll');

        $response->assertStatus(200);
        $response->assertJsonStructure([
           [
            'id',
            'code',
            'name',
            'borrowed_book'
           ],
        ]);
    }
}
