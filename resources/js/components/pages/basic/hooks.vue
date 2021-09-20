<template>
    <div>
        <h1>my name is: {{name}}</h1>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                name:'mohammad'
            }
        },
        created() {
            console.log('data:',this.name);
        }

    }
</script>
