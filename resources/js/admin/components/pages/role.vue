<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Roles
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
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(role ,i) in roles" :key="i" v-if="roles.length">
                                <td>{{ role.id }}</td>
                                <td class="_table_name">{{ role.roleName }}</td>
                                <td>{{ role.permission }}</td>
                                <td>
                                    <button v-if="isUpdatePermitted" class="_btn _action_btn edit_btn1" @click="showEditModal(role , i)"
                                            type="button">Edit
                                    </button>
                                    <button v-if="isDeletePermitted" class="_btn _action_btn make_btn1" @click="showDeleteModal(role , i)"
                                            :loading="role.isDeleting" type="error">Delete
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
                    title="Add New Role"
                    :mask-closable='false'
                >
                    <Input v-model="data.roleName" placeholder="Enter Role Name..."/>

                    <div slot="footer">
                        <Button type="default" @click="modalAllow=false" size="small">Close</Button>
                        <Button type="success" @click="addRole()" :disabled="isAdding" :loading="isAdding" size="small">
                            {{ isAdding ? 'Adding...' : 'Add Role' }}
                        </Button>
                    </div>
                </Modal>


                <!--                edit Tag-->
                <Modal
                    v-model="modalAllowEdit"
                    title="Edit tag"
                    :mask-closable='false'
                >
                    <Input v-model="editData.roleName" placeholder="Enter Role Name..."/>

                    <div slot="footer">
                        <Button  type="default" @click="modalAllowEdit=false" size="small">Close</Button>
                        <Button type="success" @click="editRole()" :disabled="isEditing" :loading="isEditing"
                                size="small">{{ isEditing ? 'Editing...' : 'Edit Role' }}
                        </Button>
                    </div>
                </Modal>

                <!--                delete Data-->
                <!--                <Modal v-model="modalAllowDelete" width="360">-->
                <!--                    <p slot="header" style="color:#f60;text-align:center">-->
                <!--                        <Icon type="ios-information-circle"></Icon>-->
                <!--                        <span>Delete confirmation</span>-->
                <!--                    </p>-->
                <!--                    <div style="text-align:center">-->
                <!--                        <p>Are You Sure to Delete????</p>-->
                <!--                    </div>-->
                <!--                    <div slot="footer">-->
                <!--                        <Button type="error" size="large" :loading="!modalAllowDelete" :disabled="!modalAllowDelete"-->
                <!--                                long @click="deleteTag(tag,i)">Delete-->
                <!--                        </Button>-->
                <!--                    </div>-->
                <!--                </Modal>-->
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
                roleName: '',
            },
            modalAllow: false,
            isAdding: false,
            isEditing: false,
            modalAllowEdit: false,
            modalAllowDelete: false,
            roles: [],
            editData: {
                roleName: '',
                permission: '',
            },
            deleteData: {},
            deleteIndex: -1,
            index: -1
        }
    },
    methods: {
        async addRole() {
            if (this.data.roleName.trim() == '') return this.error('Role Name Is Required');
            const res = await this.callApi(this.data, 'post', '/app/create_role');
            if (res.status === 201) {
                this.roles.unshift(res.data)
                this.success('Add Role SuccessFull')
                this.modalAllow = false;
                this.data.roleName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.roleName[0])
                } else {
                    this.swr()
                }
            }
        },
        async editRole() {
            if (this.editData.roleName.trim() == '') return this.error('Role Name Is Required');
            const res = await this.callApi(this.editData, 'post', '/app/edit_role');
            if (res.status === 200) {
                this.roles[this.index].roleName = this.editData.roleName
                this.success('Edit Role SuccessFull')
                this.modalAllowEdit = false;
                this.data.roleName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.roleName[0])
                } else {
                    this.swr()
                }
            }
        },
        async deleteTag() {
            const res = await this.callApi(this.deleteData, 'post', '/app/delete_role');
            if (res.status === 200) {
                this.roles.splice(this.deleteIndex, 1)
                this.success("Deleted");
                this.modalAllowDelete = false;
            } else {
                this.swr();
            }
        },
        showEditModal(role, index) {
            let obj = {
                id: role.id,
                roleName: role.roleName
            }
            this.modalAllowEdit = true
            this.editData = obj
            this.index = index
        },
        showDeleteModal(role, index) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_role',
                data: role,
                deleteIndex: index,
                listData: this.roles
            }
            this.$store.commit('setDeleteModalObj' , deleteModalObj)
            // this.modalAllowDelete = true
            // this.deleteData = tag
            // this.deleteIndex = index
        }
    },
    components:{
        deleteModal
    },

    async created() {

        const res = await this.callApi('', 'get', '/app/get_roles')
        if (res.status == 200) {
            this.roles = res.data
        } else {
            this.swr()
        }
    }
}
</script>
