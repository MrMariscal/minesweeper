<?php

namespace Tests\Unit;

use Tests\TestCase;

class MinesTest extends TestCase
{
    /**
     * Testing sites main route.
     *
     * @return void
     */
    public function testSaveMinesweeper(){
        //If we don't pass any parameters it won't fail
        $parameters = array();
        $response = $this->post('/api/save');
        $response->assertStatus(200);
    }

    public function testGetUserGames(){
        $response = $this->post('/api/userGames/', array('userid'=>1));
        $response->assertStatus(200);
        //We test we are reciving at least one valid record, we need to have saved games for this
        //and userid must be a valid user
        $response->assertJsonStructure(['0']);   
    }
}
