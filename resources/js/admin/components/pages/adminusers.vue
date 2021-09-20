<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">adminUsers
                        <Button v-if="isWritePermitted" @click="modalAllow = true">
                            <Icon type="md-add"/>
                            Add New
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Id</th>
                                <th>fullName</th>
                                <th>email</th>
                                <th></th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(adminUser ,i) in adminUsers" :key="i" v-if="adminUsers.length">
                                <td>{{ adminUser.id }}</td>
                                <td class="_table_name">{{ adminUser.fullName }}</td>
                                <td>{{ adminUser.email }}</td>
                                <td>
                                    <button v-if="isUpdatePermitted" class="_btn _action_btn edit_btn1" @click="showEditModal(adminUser , i)"
                                            type="button">Edit
                                    </button>
                                    <button v-if="isDeletePermitted" class="_btn _action_btn make_btn1" @click="showDeleteModal(adminUser , i)"
                                            :loading="adminUser.isDeleting" type="error">Delete
                                    </button>
                                </td>
                            </tr>
                            <!-- ITEMS -->

                            <!-- ITEMS -->


                        </table>
                    </div>
                </div>
                <!--                add Tag-->
                <Modal
                    v-model="modalAllow"
                    title="Add Admin User"
                    :mask-closable='false'
                >
                    <div class="space">
                        <Input type="text" v-model="data.fullName" placeholder="Enter Full Name..."/>
                    </div>
                    <div class="space">
                        <Input type="email" v-model="data.email" placeholder="Enter Email..."/>
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Enter Password..."/>
                    </div>

                    <div class="space">
                        <Select placeholder="Select User Type" v-model="data.role_id" size="large">
                            <Option :value="r.id" v-for="(r,i) in roles" :key="i" v-if="roles.length" >{{r.roleName}}</Option>

                        </Select>
                    </div>


                    <div slot="footer">
                        <Button type="default" @click="modalAllow=false" size="small">Close</Button>
                        <Button type="success" @click="addAdminUser()" :disabled="isAdding" :loading="isAdding" size="small">
                            {{ isAdding ? 'Adding...' : 'Add AdminUser' }}
                        </Button>
                    </div>
                </Modal>


                <!--                edit Tag-->
                <Modal
                    v-model="modalAllowEdit"
                    title="Edit tag"
                    :mask-closable='false'
                >
                    <Input v-model="editData.fullName" placeholder="Enter Tag Name..."/>
                    <Input v-model="editData.email" placeholder="Enter Tag Name..."/>
                    <div class="space">
                        <Select placeholder="Select User Type" v-model="data.userType" size="large">
                            <Option :value="r.id" v-for="(r,i) in roles" :key="i" v-if="roles.length" >{{r.roleName}}</Option>

                        </Select>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="modalAllowEdit=false" size="small">Close</Button>
                        <Button type="success" @click="editAdminUser()" :disabled="isEditing" :loading="isEditing"
                                size="small">{{ isEditing ? 'Editing...' : 'Edit Admin User' }}
                        </Button>
                    </div>
                </Modal>


                <deleteModal></deleteModal>
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
                fullName: '',
                email: '',
                password: '',
                role_id:''
            },
            modalAllow: false,
            isAdding: false,
            isEditing: false,
            modalAllowEdit: false,
            modalAllowDelete: false,
            adminUsers: [],
            editData: {
                fullName: '',
                email: '',
                userType: '',
            },
            deleteData: {},
            deleteIndex: -1,
            index: -1,
            roles:[]
        }
    },
    methods: {
        async addAdminUser() {
            if (this.data.fullName.trim() == '') return this.error('Full Name Is Required');
            if (this.data.email.trim() == '') return this.error('Email Is Required');
            if (this.data.password.trim() == '') return this.error('Password Is Required');
            if (!this.data.role_id) return this.error('Pleas Select a Role');
            const res = await this.callApi(this.data, 'post', '/app/create_adminUser');
            if (res.status === 201) {
                this.adminUsers.unshift(res.data)
                this.success('Add adminUser SuccessFull')
                this.modalAllow = false;
                this.data.tagName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.fullName[0])
                } else {
                    this.swr()
                }
            }
        },
        async editAdminUser() {
            if (this.editData.fullName.trim() == '') return this.error('Full Name Is Required');
            if (this.editData.email.trim() == '') return this.error('email Is Required');
            if (this.editData.userType.trim() == '') return this.error('User Type Is Required');
            const res = await this.callApi(this.editData, 'post', '/app/edit_adminUser');
            if (res.status === 200) {
                this.adminUsers[this.index].fullName = this.editData.fullName
                this.adminUsers[this.index].email = this.editData.email
                this.adminUsers[this.index].userType = this.editData.userType
                this.success('Edit AdminUser SuccessFull')
                this.modalAllowEdit = false;
                this.data.fullName = ''
                this.data.userType = ''
                this.data.email = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.adminUsers[0])
                } else {
                    this.swr()
                }
            }
        },
        async deleteTag() {
            const res = await this.callApi(this.deleteData, 'post', '/app/delete_tag');
            if (res.status === 200) {
                this.adminUsers.splice(this.deleteIndex, 1)
                this.success("Deleted");
                this.modalAllowDelete = false;
            } else {
                this.swr();
            }
        },
        showEditModal(adminUser, index) {
            let obj = {
                id: adminUser.id,
                fullName: adminUser.fullName,
                email: adminUser.email,
                userType: adminUser.userType
            }
            this.modalAllowEdit = true
            this.editData = obj
            this.index = index
        },
        showDeleteModal(adminUser, index) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_adminUser',
                data: adminUser,
                deleteIndex: index,
                listData: this.adminUsers
            }
            this.$store.commit('setDeleteModalObj' , deleteModalObj)
        }
    },
    components:{
        deleteModal
    },

    async created() {

        const [res , resRoles] = await Promise.all([
            this.callApi('', 'get', '/app/get_adminUser'),
            this.callApi('', 'get', '/app/get_roles')
        ]);
        if (res.status == 200) {
            this.adminUsers = res.data
        } else {
            this.swr()
        }
        if (resRoles.status == 200) {
            this.roles = resRoles.data
        } else {
            this.swr()
        }
    }
}
</script>
