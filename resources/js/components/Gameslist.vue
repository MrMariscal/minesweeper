<template>
    <div id="container">
        <ul id="games" class="list-unstyled">
            <li v-for="game in games" :key="game.id">
                <a :href="`/games/${game.id}`" class="btn btn-info col-sm-12">{{game.game}}</a>                       
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    name: 'gameslist',
    props: ['userid'],
    data() {
        return{
            games: null,
        }
    },
    mounted(){
        this.getGames();
    },
    methods: {
        getGames(){
            axios.post('/api/userGames', {userid: this.userid}).then(
            //axios.post('/api/userGames', {userid: 1}).then(
                response=>{
                    this.games = response.data;
                }
            );
        },
    }
}
</script>    