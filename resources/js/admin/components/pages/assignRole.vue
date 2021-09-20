<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Roles
                        <Button @click="modalAllow = true">
                            <Icon type="md-add"/>
                            Add New
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <div class="space">
                            <Select @on-change="changePermission()" placeholder="Select User Type" v-model="data.id" size="large">
                                <Option :value="r.id" v-for="(r,i) in roles" :key="i" v-if="roles.length" >{{r.roleName}}</Option>

                            </Select>
                        </div>
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Role Name</th>
                                <th>READ</th>
                                <th>WRITE</th>
                                <th>UPDATE</th>
                                <th>DELETE</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(r ,i) in resources" :key="i" v-if="resources.length">
                                <td>{{ r.recourceName }}</td>
                                <td><checkbox v-model="r.read"></checkbox></td>
                                <td><checkbox v-model="r.write"></checkbox></td>
                                <td><checkbox v-model="r.update"></checkbox></td>
                                <td><checkbox v-model="r.delete"></checkbox></td>

                            </tr>
                            <!-- ITEMS -->

                            <!-- ITEMS -->


                        </table>
                        <button v-if="isUpdatePermitted" class="info" type="info" size="large" @click="assignRole()">Assign</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import deleteModal from '../shareComponents/deleteModal';


export default {
    data() {
        return {
            data: {
                roleName: '',
                id: null,
            },
            roles: [],
            resources:[
                {recourceName:'Home' , read:false , write:false , update: false , delete:false,name:'home'},
                {recourceName:'Tags' , read:true , write:false , update: true , delete:false,name:'tags'},
                {recourceName:'Category' , read:false , write:false , update: false , delete:false,name:'category'},
                {recourceName:'Admin Users' , read:false , write:false , update: false , delete:false,name:'adminUser'},
                {recourceName:'Role' , read:false , write:false , update: false , delete:false,name:'role'},
                {recourceName:'Blogs' , read:false , write:false , update: false , delete:false,name:'blogs'},
                {recourceName:'Assign Role' , read:false , write:false , update: false , delete:false,name:'assignRole'},
                {recourceName:'Create Blog' , read:false , write:false , update: false , delete:false,name:'createBlog'},
            ],
            defaultPermission:[
                {recourceName:'Home' , read:false , write:false , update: false , delete:false,name:'home'},
                {recourceName:'Tags' , read:true , write:false , update: true , delete:false,name:'tags'},
                {recourceName:'Category' , read:false , write:false , update: false , delete:false,name:'category'},
                {recourceName:'Admin Users' , read:false , write:false , update: false , delete:false,name:'adminUser'},
                {recourceName:'Role' , read:false , write:false , update: false , delete:false,name:'role'},
                {recourceName:'Blogs' , read:false , write:false , update: false , delete:false,name:'blogs'},
                {recourceName:'Assign Role' , read:false , write:false , update: false , delete:false,name:'assignRole'},
                {recourceName:'Create Blog' , read:false , write:false , update: false , delete:false,name:'createBlog'},

            ]
        }
    },
    methods: {
        async assignRole(){
            let data = JSON.stringify(this.resources);
            const res = await this.callApi({'permission':data , 'id':this.data.id},'post','/app/assign_role');
            if(res.status = 200){
                this.success('Role Has Been Assigned Successfully!');
                if(res.data.permission){
                    this.resources = JSON.parse(res.data.permission)
                }else{
                    this.resources = this.defaultPermission
                }
            }else{
                this.swr();
            }
        },
         changePermission(){
            let index = this.roles.findIndex(role=>role.id == this.data.id);
            let permission = this.roles[index].permission
            if(!permission){
                this.resources = this.defaultPermission
            }else{
                this.resources =JSON.parse(permission)
            }
        }
    },
    components:{
        deleteModal
    },

    async created() {

        const res = await this.callApi('', 'get', '/app/get_roles')
        if (res.status == 200) {
            if(res.data.length){
                this.data.id = res.data[0].id
                this.resources = JSON.parse(res.data[0].permission)
                if (res.data[0].permission){
                    this.resources = JSON.parse(res.data[0].permission)
                }
            }
            this.roles = res.data

        } else {
            this.swr()
        }
    }
}
</script>
