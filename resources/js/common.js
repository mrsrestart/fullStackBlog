import {mapGetters} from "vuex";

export default {
    data(){
        return{
            //
        }
    },

    methods: {
        callApi(dataObj,method,url){

            try {
                return axios({
                    method: method,
                    url: url,
                    data: dataObj
                });

            }catch (e){
                return e.response
            }
        },
        info (desc,title='good') {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        success (desc,title='Greate') {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warning (desc,title='Oops') {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        error (desc,title="Oops") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr(desc = "Something Wrong! please Try again" ,title='Oops!') {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        checkUserPermission(key){
            let accessPermission = false
            if(!this.userPermissions) return true
            for(let d of this.userPermissions){
                if(this.$route.name === d.name){
                    if(d[key]){
                        accessPermission = true
                        break
                    }else{
                        break;
                    }
                }
            }
            return accessPermission;
        }

    },
    computed:{
        ...mapGetters({
           'userPermissions' : 'getUserPermissions'
        }),
        isReadPermitted(){
            return this.checkUserPermission('read')
        },
        isWritePermitted(){
            return this.checkUserPermission('write')
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update')
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete')
        }

    }
}
