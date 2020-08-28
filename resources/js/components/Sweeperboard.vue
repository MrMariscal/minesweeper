<template>
    <div id="board" class="card">
        <div class="card-title" v-if="winner==1"><h1> You Win</h1></div>
        <div class="card-title" v-if="looser==1"><h1>You Loose</h1></div>
        <div class="card-body" v-for="(row, rIndex) in mBoard" v-bind:key="rIndex">
            <div class="row" :key="count">
                <div class="col" v-for="(col, cIndex) in row" v-bind:key="cIndex">
                    <a href="#" class="btn btn-success" v-if="vBoard[rIndex][cIndex]==1" >{{mBoard[rIndex][cIndex]}}</a>
                    <a href="#" class="btn btn-success" @contextmenu.prevent="toggle(rIndex, cIndex)" v-else-if="vBoard[rIndex][cIndex]==2">?</a>
                    <a href="#" class="btn btn-success" @contextmenu.prevent="toggle(rIndex, cIndex)" v-else-if="vBoard[rIndex][cIndex]==3">F</a>
                    <div v-else-if="mBoard[rIndex][cIndex]==9">
                        <a href="#" v-if="winner == 1 || looser == 1" class="btn btn-danger" ><i class="fas fa-bomb"></i>B</a>        
                        <a href="#" v-else class="btn btn-info" @contextmenu.prevent="toggle(rIndex, cIndex)" @click.prevent="clickCell(rIndex, cIndex)" >&nbsp;</a>
                    </div>
                    <a href="#" class="btn btn-info" v-else @contextmenu.prevent="toggle(rIndex, cIndex)" @click.prevent="clickCell(rIndex, cIndex)" >
                        &nbsp;{{sBoard[rIndex][cIndex]}}
                    </a>
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
export default {
    name: 'Sweeperboard',
    props:['mainBoard', 'visibleBoard', 'rows', 'cols'],
    data(){
        return {
            mBoard: null,
            vBoard: null,
            sBoard: new Array(this.rows),
            winner: 0,
            looser: 0,
            count: 0
        }
    },
    created: function(){
        this.mBoard = this.mainBoard;
        this.vBoard = this.visibleBoard;
        for (let c = 0; c < this.cols; c++) { 
          this.sBoard[c] = new Array(this.cols); 
        } 
        for( let r = 0; r < this.rows; r++){
            for(let c = 0; c < this.cols; c++){
                this.sBoard[r][c] = ' ';
            }
        }
        console.log(this.sBoard);
    },
    methods:{ 
        clickCell(row , col){
            if (this.mBoard[row][col] == '9'){
                this.looser = 1;
            }
            else {
                let parameters = {
                    mainBoard: this.mBoard,
                    visibleBoard: this.vBoard,
                    rows: this.rows,
                    cols: this.cols,
                    row: row,
                    col: col,
                };
                axios.post('/api/click', parameters).then(response=>{
                    this.mBoard = response.data.main;
                    this.vBoard = response.data.visible;
                    if(response.data.uncovers == (this.rows*this.cols)){
                        this.winner = 1;
                    };
                });
            }
        },
        toggle(brow, bcol){
            console.log( brow + ', ' + bcol + ', ' +  this.vBoard[brow][bcol]);
            console.log(this.sBoard[brow][bcol]);
            if (this.vBoard[brow][bcol] != 1){
                switch(this.vBoard[brow][bcol]){
                    case 0:
                        //Question
                        this.vBoard[brow][bcol] = 2;
                        this.sBoard[brow][bcol] = '?';
                        break;
                    case 2:
                        //Bomb
                        this.vBoard[brow][bcol] = 3;
                        break;
                    case 3:
                        //No mark
                        this.vBoard[brow][bcol] = 0;
                        this.sBoard[brow][bcol] = ' ';
                        break;
                }
            }
            this.count++;
        }
    }
}
</script>