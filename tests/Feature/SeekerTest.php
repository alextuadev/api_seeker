<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeekerTest extends TestCase
{

    /** @test */
    public function without_params()
    {
        $response  = $this->get('/api/search');

        $response->assertJsonStructure(['code',  'message' ,  'countResult', 'data' => []])
            ->assertJson(['code' => 400,  'message' => 'The field "term" cannot be empty', 'countResult' => 0, 'data' => []])
            ->assertStatus(400);
    }

    /** @test */
    public function search_term_titanic()
    {
        $response  = $this->get('/api/search?term=titanic');

        $response->assertJsonStructure([
            'countResult',
            'data' => [
                '*' => ['name', 'type', 'image', 'origin']
            ]
        ])  ->assertJson(['countResult' => 80, 'data' => []])
            ->assertStatus(200);
    }


}

