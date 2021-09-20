import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
export default new Vuex.Store({
    state:{
        deleteModalObj: {
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            deleteIndex: -1,
            isDeleted: false,
            listData: null
        },
        user: false,
        permissions:null
    },
    getters: {
        getDeleteModalObj(state){
            return state.deleteModalObj
        },
        getUserPermissions(state){
            return state.permissions
        },

    },
    mutations: {
        setDeleteModal(state){
            state.deleteModalObj.showDeleteModal = false
        },
        setDeleteModalObj(state , data){
            state.deleteModalObj = data
        },
        updateUser(state , data){
            state.user = data
        },
        userPermission(state , data){
            state.permissions = data
        },

    },
    actions: {

    }
})
