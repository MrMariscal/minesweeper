<?php

namespace Tests\Unit;

use Tests\TestCase;

class InitializeMinesTest extends TestCase
{
    /**
     * Testing sites main route.
     *
     * @return void
     */
    public function testIndex(){
	    $response = $this->get('/');
	    $response->assertStatus(200);
    }

    public function testStartMinesweeper(){
        $parameters = ['rows'=>10, 'cols'=>10, 'mines'=>10 ];
        $response = $this->post('/api/start', $parameters);
        $response->assertStatus(200);
    }
}
