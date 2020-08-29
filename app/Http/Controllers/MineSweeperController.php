<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
class MineSweeperController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('minesweeper.index', ['userid'=>$user->id, 'username'=>$user->name]);
    }

    public function initializeBoard(Request $request){
        //We will be working with two boards:
        //Main Board, which will handle bombs and the number of neighbords with a bomb for each cell
        //Visible Board, which will handle the cells that the user has uncovered
        $rows = $request->rows;
        $cols = $request->cols;
        $mines = $request->mines;
        $mainBoard = array();
        $visibleBoard = array();
        if (is_numeric($rows) && is_numeric($cols) && is_numeric($mines)) {
            $rows = intval($rows);
            $cols = intval($cols);
            $mines = intval($mines);
            if ($rows > 1 && $cols > 1 && $mines > 1) {
                //we validate if there are more mines than cells, we set mines
                //to be 90% of total cells (Very Hard Level) 
                //[Not in the rules, but it makes the game "playable"]
                $mines = min($mines, (int)(($rows*$cols) * 0.9) );
                $mainBoard = $this->setMines($rows, $cols, $mines);
                for($iRow = 0; $iRow < $rows; $iRow++ ){
                    for ($iCol = 0; $iCol < $cols; $iCol++){
                        $visibleBoard[$iRow][$iCol] = 0;
                    }
                }
            }
        }
        return response()->json(['main'=>$mainBoard, 'visible'=>$visibleBoard]);
    }

    private function setMines($rows, $cols, $mines){
        //This is an internal method and will not be exposed
        //Here we will randomly plant the bombs on the main board
        $minesBoard = array();
        if ($mines > 0){
            $Bomb = 9;
            $minesSet = 0;
            for($iRow = 0; $iRow < $rows; $iRow++ ){
                for ($iCol = 0; $iCol < $cols; $iCol++){
                    $minesBoard[$iRow][$iCol] = 0;
                }
            }
            
            while($minesSet < $mines){
                $rRow = random_int(0,$rows-1);
                $rCol = random_int(0,$cols-1);                
                if ($minesBoard[$rRow][$rCol] != $Bomb){
                    $minesBoard[$rRow][$rCol] = $Bomb;
                    //We need to increase side cells counter and we
                    //validate not going to an invalid board index
                    for( $cRow = max(0, $rRow-1) ; $cRow <= min(($rows-1), $rRow+1) ; $cRow++ ){
                        for($cCol = max(0, $rCol-1); $cCol <= min(($cols-1), $rCol+1); $cCol++){
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

    public function clickCell(Request $request){
        //We need to explore all the neighbords to this 
        //cell and so on, easiest way to do this is recursively
        //We will get all the params and will call a function that can do that
        $iRow = $request->row;
        $iCol = $request->col;
        $iRows = $request->rows;
        $iCols = $request->cols;
        $mainBoard = $request->mainBoard;
        $visibleBoard = $request->visibleBoard;
        //We uncover all the nieghbord cells that are not currently a bomb
        $this->uncoverCells($mainBoard, $visibleBoard, $iRows, $iCols, $iRow, $iCol);
        $uncovers = $this->countUncovers($mainBoard, $visibleBoard);
        return ['main'=>$mainBoard, 'visible'=>$visibleBoard, 'uncovers'=>$uncovers];
    }

    private function uncoverCells( &$mainBoard, &$visibleBoard, $iRows, $iCols, $iRow, $iCol){
        //This is an internal method and will not be exposed
        //This function will be checking all the adyacent cells to the one specified and so on
        //as this is going to be a recursive path, we recive the boards as reference so we can update
        //them directly
        if ( $visibleBoard[$iRow][$iCol] == 0  ){
            $visibleBoard[$iRow][$iCol] = ($visibleBoard[$iRow][$iCol] == 0 ? 1 : $visibleBoard[$iRow][$iCol]);
            for($iR = max(0, $iRow-1) ; $iR <= min($iRows-1, $iRow + 1); $iR++ ){
                for($iC = max(0, $iCol-1) ; $iC <= min($iCols-1, $iCol + 1); $iC++ ){
                    if($mainBoard[$iR][$iC] != 9 ){
                        $this->uncoverCells($mainBoard, $visibleBoard, $iRows, $iCols, $iR, $iC);
                    }
                }
            }
        }
    }

    private function countUncovers($mainBoard, $visibleBoard){
        //This is an internal method and will not be exposed
        //This funcion will check how many bombs and uncovered cells we have
        //this way we can determine wether or not the game has ended
        $iBombs = 0;
        //First we check for all the bomb cells
        foreach($mainBoard as $row){
            foreach( $row as $col){
                if ($col == 9) {
                    $iBombs++;
                }
            }
        }
        //Then we check for all the visible or flagged cells
        foreach($visibleBoard as $row) {
            foreach( $row as $col){
                if ($col == 1 || $col == 3){
                    $iBombs ++;
                }
            }
        }
        return $iBombs;
    }

    public function saveGame(Request $request){
        dd($request);
    }
}
