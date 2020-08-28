<template>
    <div id="board" class="card">
        <div class="card-body" v-for="(row, rIndex) in mBoard" v-bind:key="rIndex">
            <div class="row">
                <div class="col" v-for="(col, cIndex) in row" v-bind:key="cIndex">

                    <a href="#" class="btn btn-success" v-if="vBoard[rIndex][cIndex]==1" >{{mBoard[rIndex][cIndex]}}</a>
                    <a href="#" class="btn btn-danger" v-else-if="mBoard[rIndex][cIndex]==9" >{{mBoard[rIndex][cIndex]}}</a>
                    <a href="#" class="btn btn-info" v-else @contextmenu.prevent="toggle" @click.prevent="clickCell(rIndex, cIndex)" >{{mBoard[rIndex][cIndex]}}, {{vBoard[rIndex][cIndex]}}&nbsp;</a>
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
        }
    },
    created: function(){
        this.mBoard = this.mainBoard;
        this.vBoard = this.visibleBoard;
    },
    methods:{ 
        clickCell(row , col){
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
            }).catch(

            );

        },
        toggle(){
            alert('');
        }
    }
}
</script>