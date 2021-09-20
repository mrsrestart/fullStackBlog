<template>
    <div>
        <h1>this is number change: {{counter}}</h1>
        <button @click="updateCounter(1)">up</button>
        <button @click="updateCounter(-1)">down</button>
        <div v-for="(blog,i) in blogPosts" :key="i">
            <h1>{{blog.title}}</h1>
            <h2>{{blog.content}}</h2>
        </div>


        <div>
            <h1 v-if="status">this is true</h1>
            <h1 v-else>this is false</h1>
            <button @click="changeStatus()">change</button>
        </div>

    </div>



</template>

<script>
    export default {
        data(){
            return{
                counter: 0,
                blogPosts: [],
                status: false,
            }
        },
        methods: {
            updateCounter(number){
                this.counter += number
            },
            changeStatus(){
                this.status = !this.status
            }
        },
        created() {
            let posts = [{title:'this is post 1','content': 'this is post Content 1' ,id:1},
                {title:'this is post 2','content': 'this is post Content 2' ,id:2},
                {title:'this is post 3','content': 'this is post Content 3' ,id:3},
                {title:'this is post 4','content': 'this is post Content 4' ,id:4},]
            this.blogPosts = posts;
        }
    }
</script>
