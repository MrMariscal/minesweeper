<template>
    <div id="board" class="card">
        <div class="card-title text-center" v-if="errSaving">
            <h1> You can not save and ended game</h1>
            <h2>{{sSeconds}}</h2>
        </div>
        <div class="card-title text-center" v-else-if="winner==1">
            <h1> You Win</h1>
            <h2>{{sSeconds}}</h2>
        </div>
        <div class="card-title text-center text-danger" v-else-if="looser==1">
            <h1>You Loose</h1>
            <h2>{{sSeconds}}</h2>
        </div>
        <div class="card-title text-center text-danger" v-else>
             <h2>{{sSeconds}}</h2>
       </div>
        <div class="card-body" v-for="(row, rIndex) in mBoard" v-bind:key="rIndex">
            <div class="row" :key="count">
                <div class="col" v-for="(col, cIndex) in row" v-bind:key="cIndex">
                    <div v-if="winner == 1 || looser == 1">
                        <a href="#" class="btn btn-danger"  v-if="mBoard[rIndex][cIndex]==9" ><i class="fas fa-bomb"></i></a>
                        <a href="#" class="btn btn-success" v-else >{{mBoard[rIndex][cIndex]}}</a>
                    </div> 
                    <div v-else>
                        <a href="#" class="btn btn-success" v-if="vBoard[rIndex][cIndex]==1">{{mBoard[rIndex][cIndex]}}</a>
                        <a href="#" class="btn btn-success" v-else-if="vBoard[rIndex][cIndex]==2" @contextmenu.prevent="toggle(rIndex, cIndex)" ><i class="fas fa-question-circle"></i></a>
                        <a href="#" class="btn btn-success" v-else-if="vBoard[rIndex][cIndex]==3" @contextmenu.prevent="toggle(rIndex, cIndex)" ><i class="fas fa-flag text-danger"></i></a>
                        <a href="#" class="btn btn-info" v-else @contextmenu.prevent="toggle(rIndex, cIndex)" @click.prevent="clickCell(rIndex, cIndex)">&nbsp;</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <a href="/games" class="btn btn-info">Start a New Game</a>
                <a href="#" class="btn btn-info" v-if="winner != 1 && looser != 1" @click.prevent="save">Save & pause this game</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Sweeperboard',
    props:['userid', 'mainBoard', 'visibleBoard', 'rows', 'cols', 'seconds'],
    data(){
        return {
            mBoard: null,
            vBoard: null,
            winner: 0,
            looser: 0,
            count: 0,
            sSeconds: 0,
            errSaving: false,
        }
    },
    created: function(){
        this.mBoard = this.mainBoard;
        this.vBoard = this.visibleBoard;
        this.sSeconds = this.seconds;
    },
    mounted(){
        this.secondsUpdater();
    },
    methods:{ 
        clickCell(row , col){
            if (this.mBoard[row][col] == '9'){
                clearInterval(this.interval);
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
                    timer: 0,
                    interval: 0
                };
                axios.post('/api/click', parameters).then(response=>{
                    this.mBoard = response.data.main;
                    this.vBoard = response.data.visible;
                    if(response.data.uncovers == (this.rows*this.cols)){
                        clearInterval(this.interval);
                        this.winner = 1;
                    };
                });
            }
        },

        toggle(brow, bcol){
            if (this.vBoard[brow][bcol] != 1){
                switch(this.vBoard[brow][bcol]){
                    case 0:
                        //Question
                        this.vBoard[brow][bcol] = 2;
                        break;
                    case 2:
                        //Bomb
                        this.vBoard[brow][bcol] = 3;
                        break;
                    case 3:
                        //No mark
                        this.vBoard[brow][bcol] = 0;
                        break;
                }
            }
            this.count++;
        },

        secondsUpdater(){
            this.interval = setInterval(()=>{
                this.sSeconds++;
            },1000);
        },

        save(){
            if (this.winner == 1 || this.looser == 1){
                this.errSaving = true;
            }
            else {
                let name = prompt('Name your game', 'Game -  ');
                if (name != null ){
                    let game = {                        
                        userid: this.userid,
                        gamename: name,
                        seconds: this.sSeconds,
                        mainBoard: this.mBoard,
                        visibleBoard: this.vBoard,                
                    }
                    axios.post('/api/save', game);
                    //window.location ='/games';            
                }
            }
        }
    }
}
</script>