<template>
    <div id="container">
        <div id="setup" class="card">
            <div class="card-body">
                <h1>Minesweeper</h1>
                Setup Game
                <div id="setup">
                    <form action="">
                        <div class="input-group col-sm-12">
                            <div class="col-sm-3">
                                <label for="rows">Rows:</label>
                            </div>
                            <div class="col-sm-9">
                                <input name="rows" id="rows" type="number" v-model="parameters.rows" />
                            </div>
                        </div>
                        <div class="input-group col-sm-12">
                            <div class="col-sm-3">
                                <label for="rows">Cols:</label>
                            </div>
                            <div class="col-sm-9">
                                <input name="cols" id="cols" type="number" v-model="parameters.cols" />
                            </div>
                        </div>
                        <div class="input-group col-sm-12">
                            <div class="col-sm-3">
                                <label for="mines">Mines:</label>
                            </div>
                            <div class="col-sm-9">
                                <input name="mines" id="mines" type="number" v-model="parameters.mines" />
                            </div>
                        </div>
                        <div class="input-group-col-sm-12 text-right">
                            <a href="#" @click.prevent="createNewBoard" class="btn btn-info">Create Board</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="board" class="card">
            <div class="card-body" v-for="(row, rIndex) in mainBoard">
                <div class="row">
                    <div class="col" v-for="(col, cIndex) in row">
                        <a href="#" class="btn btn-success">{{rIndex}},{{cIndex}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'minesweeper',
    data() {
        return{
            setup: false, 
            mainBoard: null,
            visibleBoard: null,
            boardHtml: '',
            parameters: {
                rows: 10,
                cols: 10,
                mines:10,
            },
        }
    },
    methods: {
        createNewBoard(){
            axios.post('/api/start', this.parameters).then(
                response=>{
                    this.mainBoard = response.data.main;
                    this.visibleBoard = response.data.visible;
                    this.showBoard();
                }
                );
        },
        showBoard(){
            let html = '';
            for( let r = 0 ; r < this.parameters.rows ; r++ ){
                console.log(r);
                console.log(html);
                html = '<div class="row">';
                for( let c = 0 ; c < this.parameters.cols ; c++ ){
                    let char = '';
                    switch (this.visibleBoard[r][c] == 0){
                        case 0:
                            char += '&nbsp';
                            break;
                        case 1:
                            if( this.mainBoard[r][c] == 9){
                                char += '<i class="fas fa-bomb"></i>'
                            }
                            else{
                                char += this.mainBoard[r][c];
                            }
                            break;
                        default:
                            char += '&nbsp';
                            break;
                    }
                    html += '<div class="col">';
                    html += '<a href="#" class="btn btn-success" @click.prevent="">' + char + '</a>'
                    html += '</div>';
                }
                html += '</div>';
                this.boardHtml = html;
            }
            
        }
    }
}
</script>    