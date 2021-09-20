<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Tags
                        <Button @click="modalAllow = true">
                            <Icon type="md-add"/>
                            Add New
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Id</th>
                                <th>Tag</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(tag ,i) in tags" :key="i" v-if="tags.length">
                                <td>{{ tag.id }}</td>
                                <td class="_table_name">{{ tag.tagName }}</td>
                                <td>{{ tag.created_at }}</td>
                                <td>
                                    <button class="_btn _action_btn edit_btn1" @click="showEditModal(tag , i)"
                                            type="button">Edit
                                    </button>
                                    <button class="_btn _action_btn make_btn1" @click="showDeleteModal(tag , i)"
                                            :loading="tag.isDeleting" type="error">Delete
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
                    title="Add tag"
                    :mask-closable='false'
                >
                    <Input v-model="data.tagName" placeholder="Enter Tag Name..."/>

                    <div slot="footer">
                        <Button type="default" @click="modalAllow=false" size="small">Close</Button>
                        <Button type="success" @click="addTag()" :disabled="isAdding" :loading="isAdding" size="small">
                            {{ isAdding ? 'Adding...' : 'Add Tag' }}
                        </Button>
                    </div>
                </Modal>


                <!--                edit Tag-->
                <Modal
                    v-model="modalAllowEdit"
                    title="Edit tag"
                    :mask-closable='false'
                >
                    <Input v-model="editData.tagName" placeholder="Enter Tag Name..."/>

                    <div slot="footer">
                        <Button type="default" @click="modalAllowEdit=false" size="small">Close</Button>
                        <Button type="success" @click="editTag()" :disabled="isEditing" :loading="isEditing"
                                size="small">{{ isEditing ? 'Editing...' : 'Edit Tag' }}
                        </Button>
                    </div>
                </Modal>

                <!--                delete Data-->
                <Modal v-model="modalAllowDelete" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are You Sure to Delete????</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" :loading="!modalAllowDelete" :disabled="!modalAllowDelete"
                                long @click="deleteTag(tag,i)">Delete
                        </Button>
                    </div>
                </Modal>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            data: {
                tagName: '',
            },
            modalAllow: false,
            isAdding: false,
            isEditing: false,
            modalAllowEdit: false,
            modalAllowDelete: false,
            tags: [],
            editData: {
                tagName: '',
            },
            deleteData: {},
            deleteIndex: -1,
            index: -1
        }
    },
    methods: {
        async addTag() {
            if (this.data.tagName.trim() == '') return this.error('Tag Name Is Required');
            const res = await this.callApi(this.data, 'post', 'app/create_tag');
            if (res.status === 201) {
                this.tags.unshift(res.data)
                this.success('Add Tag SuccessFull')
                this.modalAllow = false;
                this.data.tagName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.tagName[0])
                } else {
                    this.swr()
                }
            }
        },
        async editTag() {
            if (this.editData.tagName.trim() == '') return this.error('Tag Name Is Required');
            const res = await this.callApi(this.editData, 'post', 'app/edit_tag');
            if (res.status === 200) {
                this.tags[this.index].tagName = this.editData.tagName
                this.success('Edit Tag SuccessFull')
                this.modalAllowEdit = false;
                this.data.tagName = ''
            } else {
                if (res.status === 422) {
                    this.error(res.data.errors.tagName[0])
                } else {
                    this.swr()
                }
            }
        },
        async deleteTag() {
            const res = await this.callApi(this.deleteData, 'post', 'app/delete_tag');
            if (res.status === 200) {
                this.tags.splice(this.deleteIndex, 1)
                this.success("Deleted");
                this.modalAllowDelete = false;
            } else {
                this.swr();
            }
        },
        showEditModal(tag, index) {
            let obj = {
                id: tag.id,
                tagName: tag.tagName
            }
            this.modalAllowEdit = true
            this.editData = obj
            this.index = index
        },
        showDeleteModal(tag, index) {
            this.modalAllowDelete = true
            this.deleteData = tag
            this.deleteIndex = index
        }
    },

    async created() {

        const res = await this.callApi('', 'get', 'app/get_tag')
        if (res.status == 200) {
            this.tags = res.data
        } else {
            this.swr()
        }
    }
}
</script>
