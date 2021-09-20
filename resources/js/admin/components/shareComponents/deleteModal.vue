<template>
    <div>
        <Modal
            :value="getDeleteModalObj.showDeleteModal"
            :mask-closable='false'
            :closable="false"
               width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>Are You Sure to Delete????</p>
            </div>
            <div slot="footer">
                <Button type="default" size="large"
                        @click="closeTag">Close
                </Button>
                <Button type="error" size="large"
                         @click="deleteTag">Delete
                </Button>


            </div>
        </Modal>
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {

    data() {
        return{

        }
    },
    methods: {
        async deleteTag() {
            this.$store.commit('setDeleteModal')
            const res = await this.callApi(this.getDeleteModalObj.data, 'post',this.getDeleteModalObj.deleteUrl);
            if (res.status === 200) {
                this.getDeleteModalObj.listData.splice(this.getDeleteModalObj.deleteIndex, 1)
                this.success("Deleted");
                this.modalAllowDelete = false;
            } else {
                this.swr();
            }
        },
        closeTag(){
            this.$store.commit('setDeleteModal')
        }
    },
    computed:{
        ...mapGetters(['getDeleteModalObj'])

    }

}
</script>
