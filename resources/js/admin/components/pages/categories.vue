<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Categories
                        <Button v-if="isWritePermitted" @click="modalAllow = true">
                            <Icon type="md-add"/>
                            Add New Category
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Id</th>
                                <th>Category Name</th>
                                <th>Category Icon</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(cat ,i) in cats" :key="i" v-if="cats.length">
                                <td>{{ cat.id }}</td>
                                <td class="_table_name">{{ cat.categoryName }}</td>
                                <td ><img v-if="cat.iconImage" style="width: 100px;" :src="'/uploads/'+ cat.iconImage " alt=""></td>
                                <td>{{ cat.created_at }}</td>
                                <td>
                                    <button v-if="isUpdatePermitted" class="_btn _action_btn edit_btn1" @click="showEditModal(cat , i)"
                                            type="button">Edit
                                    </button>
                                    <button v-if="isDeletePermitted" class="_btn _action_btn make_btn1" @click="showDeleteModal(cat , i)"
                                            :loading="cat.isDeleting" type="error">Delete
                                    </button>
                                </td>
                            </tr>
                            <!-- ITEMS -->

                            <!-- ITEMS -->


                        </table>
                    </div>
                </div>
                <!--                add Category-->
                <Modal
                    v-model="modalAllow"
                    title="Add Cat"
                    :mask-closable='false'
                >
                    <Input v-model="data.categoryName" placeholder="Enter Category Name..."/>

                    <Upload
                        ref="uploads"
                        v-model="data.iconImage"
                        multiple
                        type="drag"
                        action="app/upload"
                        :on-success="handleSuccess"
                        :headers="{'x-csrf-token' : token , 'X-Requested-With': 'XMLHttpRequest'}"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        :on-error="handleError"
                    >

                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-if="this.data.iconImage">
                        <img :src = "'/uploads/'+ this.data.iconImage" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteImage()" />
                        </div>
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="modalAllow=false" size="small">Close</Button>
                        <Button type="success" @click="addCat()" :disabled="isAdding" :loading="isAdding" size="small">
                            {{ isAdding ? 'Adding...' : 'Add Cat' }}
                        </Button>
                    </div>
                </Modal>


                <!--                edit Category-->
                <Modal
                    v-model="modalAllowEdit"
                    title="Edit Category"
                    :mask-closable='false'
                >
                    <Input v-model="editData.categoryName" placeholder="Enter Category Name..."/>
                    <Upload
                        v-model="editData.iconImage"
                        multiple
                        type="drag"
                        action="app/upload"
                        :on-success="handleSuccessEdit"
                        :headers="{'x-csrf-token' : token , 'X-Requested-With': 'XMLHttpRequest'}"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        :on-error="handleError"
                    >

                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="img-responsive" v-if="this.editData.iconImage">
                        <img :src = "'/uploads/'+ this.editData.iconImage" alt="">
                    </div>
                    <div slot="footer">
                        <Button type="default" @click="modalAllowEdit=false" size="small">Close</Button>
                        <Button type="success" @click="editCag()" :disabled="isEditing" :loading="isEditing"
                                size="small">{{ isEditing ? 'Editing...' : 'Edit Category' }}
                        </Button>
                    </div>
                </Modal>

                <!--                delete Category-->
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
<!--                                long @click="deleteCat()">Delete-->
<!--                        </Button>-->
<!--                    </div>-->
<!--                </Modal>-->
                <deleteModal></deleteModal>
            </div>
        </div>
    </div>
</template>


<script>
import deleteModal from "../shareComponents/deleteModal";
export default {
    data() {
        return {
            data: {
                iconImage: '',
                categoryName: '',
            },
            modalAllow: false,
            isAdding: false,
            isEditing: false,
            modalAllowEdit: false,
            modalAllowDelete: false,
            cats: [],
            editData: {
                id: 0,
                categoryName: '',
                iconImage: '',
            },
            deleteData: {},
            deleteIndex: -1,
            index: -1,
            token: ''
        }
    },
    methods: {
        async addCat() {
            if (this.data.categoryName.trim() == '') return this.error('Category Name Is Required');
            const res = await this.callApi(this.data, 'post', '/app/create_cat');
            if (res.status === 201) {
                this.cats.unshift(res.data)
                this.success('Add Cat SuccessFull')
                this.modalAllow = false;
                this.data.categoryName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.categoryName[0])
                } else {
                    this.swr()
                }
            }
        },
        async editCag() {
            if (this.editData.categoryName.trim() == '') return this.error('Cat Name Is Required');
            console.log(this.editData);
            const res = await this.callApi(this.editData, 'post', '/app/edit_cat');
            if (res.status === 200) {
                this.cats[this.index].categoryName = this.editData.categoryName
                this.cats[this.index].iconImage = this.editData.iconImage
                this.success('Edit Category SuccessFull')
                this.modalAllowEdit = false;
                this.data.categoryName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.categoryName[0])
                } else {
                    this.swr()
                }
            }
        },
        async deleteCat() {
            const res = await this.callApi(this.deleteData, 'post', '/app/delete_cat');
            if (res.status === 200) {
                this.cats.splice(this.deleteIndex, 1)
                this.success("Deleted");
                this.modalAllowDelete = false;
            } else {
                this.swr();
            }
        },
        showEditModal(cat, index) {
            let obj = {
                id: cat.id,
                categoryName: cat.categoryName,
                iconImage: cat.iconImage
            }
            this.modalAllowEdit = true
            this.editData = obj
            this.index = index
        },
        showDeleteModal(cat, index) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_cat',
                data: cat,
                deleteIndex: index,
                listData: this.cats
            }
            this.$store.commit('setDeleteModalObj' , deleteModalObj)

            // this.modalAllowDelete = true
            // this.deleteData = cat
            // this.deleteIndex = index
        },
        handleView(name) {
            this.iconImage = name;
            this.visible = true;
        },
        handleRemove(file) {
            const fileList = this.$refs.upload.fileList;
            this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
        },
        handleSuccess(res, file) {
            this.data.iconImage = res
        },
        handleError(res, file) {
            this.$Notice.warning({
                title: 'The file Format is incorrect!!',
                desc: file.errors.file[0]
            })
        },
        handleFormatError(file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize(file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        handleViewEdit(name) {
            this.editData.iconImage = name;
            this.visible = true;
        },
        handleSuccessEdit(res, file) {
            this.editData.iconImage = res
        },
        async deleteImage(){
            let image = this.data.iconImage
            let res = await this.callApi(this.data,'post','/app/delete_image');
            this.$refs.uploads.clearFiles()
            this.data.iconImage = ''
            if (res.status != 200){
                this.data.iconImage = image
                this.swr()
            }
        }
    },
    components:{
        deleteModal
    },
    async created() {
        this.token = window.Laravel.csrfToken;
        const res = await this.callApi('', 'get', '/app/get_cat')
        if (res.status == 200) {
            this.cats = res.data
        } else {
            this.swr()
        }
    }
}
</script>
