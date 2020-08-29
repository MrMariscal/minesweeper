<template>
    <div id="container">
        <div id="setup" class="card" v-if="showSetup">
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
        <div class="card" v-if="showBoard">
            <div class="card-body">
                <Sweeperboard :userid="userid" :mainBoard="mainBoard" :visibleBoard="visibleBoard" :rows="parameters.rows" :cols="parameters.cols" :seconds="seconds"></Sweeperboard>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'minesweeper',
    props:['userid', 'game'],
    data() {
        return{
            setup: false, 
            mainBoard: null,
            visibleBoard: null,
            showBoard: false,
            showSetup: true,
            jGame: null,
            boardHtml: '',
            seconds: 0,
            parameters: {
                rows: 10,
                cols: 10,
                mines:10,
            },
        }
    },
    mounted(){
        if ( Object.keys(this.game).length > 2 ){
            this.jGame = JSON.parse(this.game);
            this.mainBoard = this.jGame.main;
            this.visibleBoard = this.jGame.visible;
            this.seconds = this.jGame.seconds;
            this.recreateNewBoard();
        }
    },
    methods: {
        createNewBoard(){
            axios.post('/api/start', this.parameters).then(
                response=>{
                    this.mainBoard = response.data.main;
                    this.visibleBoard = response.data.visible;
                    this.showBoard = true;
                    this.showSetup = false;
                    this.seconds = 0;
                }
                );
        },
        recreateNewBoard(){
            this.showBoard = true;
            this.showSetup = false;
        },
    }
}
</script>    