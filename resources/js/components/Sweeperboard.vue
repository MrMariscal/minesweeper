<template>
    <div id="board" class="card">
        <div class="card-body" v-for="(row, rIndex) in mainBoard" v-bind:key="rIndex">
            <div class="row">
                <div class="col" v-for="(col, cIndex) in row" v-bind:key="cIndex">
                    <a href="#" class="btn btn-success" @click.prevent="clickCell(rIndex, cIndex)" >{{mainBoard[rIndex][cIndex]}}</a>
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

        }
    },
    methods:{ 
        clickCell(row , col){
            let parameters = {
                mainBoard: this.mainBoard,
                visibleBoard: this.visibleBoard,
                rows: this.rows,
                cols: this.cols,
                row: row,
                col: col,
            };

        },
    }
}
</script>