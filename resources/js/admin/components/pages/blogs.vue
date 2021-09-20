<template>
    <div>
        <div class="content">
            <div class="container">
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">blogs
                        <Button v-if="isWritePermitted" @click="$router.push('/createBlog')">
                            <Icon type="md-add"/>
                            Create Blog
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Id</th>
                                <th>BlogTitle</th>
                                <th>Categories</th>
                                <th>Tags</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->


                            <!-- ITEMS -->
                            <tr v-for="(blog ,i) in blogs" :key="i" v-if="blogs.length">
                                <td>{{ blog.id }}</td>
                                <td class="_table_name">{{ blog.title }}</td>
                                <td>
                                    <p v-for="(c,i) in blog.cat" :key="i">
                                        <Tag type="border">
                                            {{ c.categoryName  }}
                                        </Tag>
                                    </p>
                                </td>
                                <td>
                                    <p v-for="(t,i) in blog.tag" :key="i">
                                        <Tag type="border">
                                            {{ t.tagName }}
                                        </Tag>
                                    </p>
                                </td>
                                <td>
                                    <button class="_btn _action_btn edit_btn1" v-if="isUpdatePermitted"  @click="$router.push('/edit_blog/'+blog.id)"
                                            type="button">Edit
                                    </button>
                                    <button class="_btn _action_btn edit_btn1" v-if="isUpdatePermitted"  @click="showEditModal(blog , i)"
                                            type="button">View
                                    </button>
                                    <button class="_btn _action_btn make_btn1" v-if="isDeletePermitted" @click="showDeleteModal(blog , i)"
                                            :loading="blog.isDeleting" type="error">Delete
                                    </button>
                                </td>
                            </tr>
                            <!-- ITEMS -->

                            <!-- ITEMS -->


                        </table>
                    </div>
                </div>




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
            modalAllowDelete: false,
            blogs: [],
            deleteData: {},
            deleteIndex: -1,
            index: -1
        }
    },
    methods: {

        async deleteTag() {
            const res = await this.callApi(this.deleteData, 'post', '/app/delete_tag');
            if (res.status === 200) {
                this.blogs.splice(this.deleteIndex, 1)
                this.success("Deleted");
                this.modalAllowDelete = false;
            } else {
                this.swr();
            }
        },

        showDeleteModal(blog, index) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_blog',
                data: blog,
                deleteIndex: index,
                listData: this.blogs
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
        const res = await this.callApi('', 'get', '/app/get_blogs')
        if (res.status == 200) {
            this.blogs = res.data
        } else {
            this.swr()
        }
    }
}
</script>
