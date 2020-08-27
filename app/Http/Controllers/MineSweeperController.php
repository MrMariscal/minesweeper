<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MineSweeperController extends Controller
{
    public function initializeBoard($rows, $cols, $mines){
        $mainBoard = array();
        $visibleBoard = array();
        if (is_numeric($rows) && is_numeric($cols) && is_numeric($mines)) {
            if ($rows > 0 && $cols > 0 && $mines > 0) {
                //we validate if there are more mines than cells, we set mines
                //to be 90% of total cells (Very Hard Level) 
                //[Not in the rules, but it makes the game "playable"]
                $mines = min($mines, (int)(($rows*$cols) * 0.9) );
                $mainBoard = $this->setMines($rows, $cols, $mines);
                for($iRow = 0; $iRow < $rows; $iRow++ ){
                    for ($iCol = 0; $iCol < $cols; $iCol++){
                        $visibleBoard[$iRow][$iCol] = false;
                    }
                }
            }
        }	
    }

    public function setMines($rows, $cols, $mines){
        $minesBoard = array();
        if ($mines > 0){
            $Bomb = config('Bomb');
            $minesSet = 0;
            for($iRow = 0; $iRow < $rows; $iRow++ ){
                for ($iCol = 0; $iCol < $cols; $iCol++){
                    $minesBoard[$iRow][$iCol] = 0;
                }
            }

            while($minesSet < $mines){
                $rRow = random_int(1,$mines);
                $rCol = random_int(1,$mines);
                //We verify that the cell is not already a mine
                if ($minesBoard[$rRow][$rCol] != $Bomb){
                    $minesBoard[$rRow][$rCol] = $Bomb;
                    //We need to increase side cells counter and we
                    //validate not going to an invalid board index
                    for( $cRow = max(0, $rRow-1) ; $cRow <= min($rows, $rRow+1) ; $cRow++ ){
                        for($cCol = max(0, $rCol-1); $cCol <= min($cols, $cCol+1); $cCol++){
                            if ($minesBoard[$cRow][$cCol] != $Bomb){
                                $minesBoard[$cRow][$cCol]++;
                            }
                        }
                    }
                    $minesSet++;
                }
            }
        }
        return $minesBoard;
    }
}
