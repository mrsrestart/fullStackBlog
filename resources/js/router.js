import Vue from "vue"
import Router from "vue-router"
Vue.use(Router)
import myFirstPage from "./components/pages/myFirstPage"
import newPage from "./components/pages/newPage"
import hooks from "./components/pages/basic/hooks"
import methods from "./components/pages/basic/methods"
import home from "./components/pages/home"
import tags from "./admin/components/pages/tags"
import category from "./admin/components/pages/categories"
import createBlog from "./admin/components/pages/craeteBlog"
import adminUsers from "./admin/components/pages/adminusers"
import login from "./admin/components/pages/login"
import role from "./admin/components/pages/role"
import assignRole from "./admin/components/pages/assignRole"
import blogs from "./admin/components/pages/blogs";
import editBlog from "./admin/components/pages/editBlog";
import notFound from "./admin/components/pages/notFound";
const routes = [
    {
        path: '/',
        component: home,
        name:'home'
    },
    {
        path: '/tags',
        component: tags,
        name:'tags'
    },
    {
        path: '/category',
        component: category,
        name:'category'
    },
    {
        path: '/my-new-vue-router',
        component: myFirstPage,
        name:'myFirstPage'
    },
    {
        path: '/createBlog',
        component: createBlog,
        name:'createBlog'
    },
    {
        path: '/newPage',
        component: newPage,
        name:'newPage'
    },


    //hooks
    {
        path: '/hooks',
        component: hooks,
        name:'hooks'
    },
    {
        path: '/methods',
        component: methods,
        name:'methods'
    },
    {
        path: '/adminUser',
        component: adminUsers,
        name:'adminUsers'
    },
    {
        path: '/login',
        component: login,
        name:'login'
    },
    {
        path: '/role',
        component: role,
        name:'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name:'assignRole'
    },
    {
        path: '/blogs',
        component: blogs,
        name:'blogs'
    },
    {
        path: '/edit_blog/:id',
        component: editBlog,
        name:'editBlog'
    },
    {
        path: '*',
        component: notFound,
        name:'notfound'
    }
]

export default new Router({
    mode: 'history',
    routes
})
