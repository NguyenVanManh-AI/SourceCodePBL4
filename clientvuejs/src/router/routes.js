import { createRouter, createWebHistory } from 'vue-router'
// import useEventBus from '../composables/useEventBus'


// admin
import AdminComp from './../components/Admin'
import LoginAdmin from './../components/admin/Login'
import DashboardAdmin from './../components/admin/Dashboard'
import ProfileAdmin from './../components/admin/Profile'

import CategoryAdmin from './../components/admin/Category'
import CustomerAdmin from './../components/admin/Customer'
import ManagementAdmin from './../components/admin/ManagementAdmin'
import OrderAdmin from './../components/admin/Order'
import ProductAdmin from './../components/admin/Product'
import ProviderAdmin from './../components/admin/Provider'
import StatisticalAdmin from './../components/admin/Statistical'
import ProductDetails from './../components/admin/ProductDetails'

// user 
import UserComp from './../components/User'
import LoginUser from './../components/user/Login'
import DashboardUser from './../components/user/Dashboard'
import InforUser from './../components/user/InforUser'


// other 
import NotFound from './../components/NotFound'
import ChangeComp from './../components/Change'

// import Register from './../components/CompRegister'
// import Post from './../components/CompPost'
// import PostEdit from './../components/CompPostEdit'
// import DetailPost from './../components/DetailPost'
// import MyPost from './../components/MyPosts'
// import PostNew from './../components/CompPostNew'
// import CompError from './../components/CompError'
// import NotFound from './../components/NotFound'
// import InFor from './../components/Infor'
// import Menu from './../components/Menu'
// import ForgotPassword from './../components/ForgotPassword'

const routes = [

    {
        path: '/main',
        component: UserComp,
        name:'UserComp',
        children : [
            {path:'login',name:'LoginUser',component:LoginUser},
            {path:'dashboard',name:'DashboardUser',component:DashboardUser},
            {path:'infor-user',name:'InforUser',component:InforUser},
        ]
    },   
    {
        path: '/admin',
        component: AdminComp,
        name:'AdminComp',
        children : [
            {path:'login',name:'LoginAdmin',component:LoginAdmin},
            {path:'dashboard',name:'DashboardAdmin',component:DashboardAdmin},
            {path:'profile-admin',name:'ProfileAdmin',component:ProfileAdmin},

            {path:'category',name:'CategoryAdmin',component:CategoryAdmin},
            {path:'customer',name:'CustomerAdmin',component:CustomerAdmin},
            {path:'management-admin',name:'ManagementAdmin',component:ManagementAdmin},
            {path:'order',name:'OrderAdmin',component:OrderAdmin},
            {path:'product',name:'ProductAdmin',component:ProductAdmin},
            {path:'product/:id',name:'ProductDetails',component:ProductDetails},
            {path:'provider',name:'ProviderAdmin',component:ProviderAdmin},
            {path:'statistical',name:'StatisticalAdmin',component:StatisticalAdmin},
        ]
    }, 
    {path: '/',component: ChangeComp,name:'ChangeComp'},
    {path: '/:NotFound(.*)*',component: NotFound,name:'NotFound'}


//     {path: '/login',component: Login,name:'Login'},    
//     {path: '/register',component: Register,name:'Register'} ,
//     {path: '/reset-password',component: ForgotPassword,name:'ForgotPassword'} ,
//     {path: '',component: Post,name:'Post'}, 
//     {path: '/my',component: MyPost,name:'MyPost'}, 
//     {path: '/posts/:id',component: DetailPost,name:'DetailPost'}, 
//     {path: '/post/edit',component: PostEdit,name:'PostEdit'}, 
//     {path: '/post/new',component: PostNew,name:'PostNew'}, 
//     {path: '/error',component: CompError,name:'CompError'}, 
//     {path: '/infor',component: InFor,name:'InFor'}, 
//     {path: '/menu',component: Menu,name:'Menu'}, 
//     {path: '/:NotFound(.*)*',component: NotFound,name:'NotFound'}
    
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

router.beforeEach((to,from,next)=>{
    if(to.path.includes('/main')){
        let excludePages = ['/main/login','/main/dashboard'];
        let requiredlogin = !excludePages.includes(to.path);
        let user = localStorage.getItem('user');
        if(requiredlogin && !user){
            if(to.path != "/main"){
                next({name:'LoginUser'});
                alert("Bạn chưa đăng nhập !");
            }
        }
    }

    if(to.path.includes('/admin')){
        let excludePages = ['/admin/login','/admin/dashboard'];
        let requiredlogin = !excludePages.includes(to.path);
        let admin = localStorage.getItem('admin');
        if(requiredlogin && !admin){
            if(to.path != "/admin"){
                next({name:'LoginAdmin'});
                alert("Admin chưa đăng nhập !");
            }
        }
    }
    next();





    // if(!to.path.indexOf('/admin')){
    //     let excludePages = ['/admin/login','/admin/dashboard'];
    //     let requiredlogin = !excludePages.includes(to.path);
    //     let user = localStorage.getItem('admin');
    //     if(requiredlogin && !user){
    //         next({name:'LoginAdmin'});
    //         console.log("Admin chưa đăng nhập !");
    //     }
    // }

    // let excludePages = ['/login','/register','/reset-password'];
    // let requiredlogin = !excludePages.includes(to.path);
    // let user = localStorage.getItem('user');
    // if(requiredlogin && !user){
    //     if(to.path != "/" && to.path.indexOf('/posts/')){
    //         next({name:'Login'});
    //         alert("Bạn chưa đăng nhập !");
    //     }
    // }

})

// router.beforeEach((to,from,next)=>{
//     let excludePages = ['/login','/register','/reset-password','/menu'];
//     let requiredlogin = !excludePages.includes(to.path);
//     let user = localStorage.getItem('user');
//     if(requiredlogin && !user){
//         if(to.path != "/" && to.path.indexOf('/posts/')){
//             next({name:'Login'});
//             alert("Bạn chưa đăng nhập !");
//         }
//     }

//     if(to.path.indexOf('/posts/')){
//         const { emitEvent } = useEventBus();
//         emitEvent('geneEvent_route',null);
//     }
//     next();
// })


// router.beforeEach((to,from,next)=>{
//     let excludePages1 = ['/login','/register','/reset-password'];
//     let excludePages2 = ['/login','/register','/reset-password'];
//     let requiredlogin1 = !excludePages1.includes(to.path);
//     let requiredlogin2 = !excludePages2.includes(to.path);

//     let user = localStorage.getItem('user');
//     let admin = localStorage.getItem('admin');

//     if(requiredlogin1 && !user){
//         if(to.path != "/" && to.path.indexOf('/posts/')){
//             next({name:'Login'});
//             alert("Bạn chưa đăng nhập !");
//         }
//     }

//     if(requiredlogin2 && !admin){
//         if(to.path != "/" && to.path.indexOf('/posts/')){
//             next({name:'Login'});
//             alert("Bạn chưa đăng nhập !");
//         }
//     }

//     if(to.path.indexOf('/posts/')){
//         const { emitEvent } = useEventBus();
//         emitEvent('geneEvent_route',null);
//     }
//     next();
// })





export default router
