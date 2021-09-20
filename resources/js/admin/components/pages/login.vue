<template>
    <div>
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                    <h2 class="headerLogin">Login To Dashboard</h2>
                    <Input type="email" v-model="data.email" placeholder="Enter Email"/>


                    <Input type="password" v-model="data.password" placeholder="****"/>


                    <div class="buttonFooter" slot="footer">
                        <Button type="info" @click="login" size="large">Login</Button>

                    </div>

                </div>

            </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                data:{
                    email:'',
                    password:''
                }
            }
        },
        methods: {
           async login(){
            if(this.data.email.trim() == '') return this.error('Email Is Required')
            if(this.data.password.trim() == '') return this.error('Password Is Required')
            if(this.data.password.length < 6) return this.error('Incorrect Login Details')
            const res = await this.callApi(this.data ,'post','/app/admin_login')
               if (res.status == 201) {
                   this.success(res.data.msg)
                   window.location="/"
               } else {
                   this.error(res.data.msg)
               }

           }
        }
    }
</script>
<style scoped>
    ._1adminOverveiw_table_recent{
        margin: 0 auto;
        margin-top: 10%;
    }
    .headerLogin{
        margin-bottom: 5%;
        text-align: center;
    }
    .buttonFooter{
        text-align: center;
    }
</style>
