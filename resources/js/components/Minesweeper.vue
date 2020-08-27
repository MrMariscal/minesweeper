<template>
    <div>
        <h1>Minesweeper</h1>
    </div>
</template>
<script>
export default {
    name: 'minesweeper',
    data() {
        return{
            parameters: {
                rows: 10,
                cols: 10,
                mines:10,
            },
            mainBoard: null,
            visibleBoard: null,
        }
    },
    created: ()=>{
        axios.post('/api/start', this.parameters).then(response=>{
            console.log(response);
        });
    }
}
</script>    