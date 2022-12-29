<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spadministrator" id="spadministrator">Administrator</span></div>
                    <div style="font-weight: bold">Administrator</div>
                </div>
                <div id="search">
                    <div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span @click="clicksearch" class="input-group-text" id="validationTooltipUsernamePrepend"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                            <input v-model="searchad" style="width:400px;border-top-right-radius: 6px;border-bottom-right-radius: 6px;" type="text" class="form-control" id="validationTooltipUsername" placeholder="Search Information Admin" aria-describedby="validationTooltipUsernamePrepend" required>
                            <div class="invalid-tooltip">
                                Please choose a unique and valid username.
                            </div>
                        </div>
                    </div>
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>

            <div id="table">
                <div id="toptable">
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-user-shield"></i> Administrator Table</div>
                    <div id="add_button"><button type="submit" class="mt-4 btn-pers" data-toggle="modal" data-target="#exampleModalAddAdmin" ><i class="fa-solid fa-user-plus"></i></button></div>
                    <!-- Model Add Admin -->
                    <div class="modal fade" id="exampleModalAddAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><i class="fa-brands fa-keycdn"></i> Add Account Admin</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label"><i class="fa-solid fa-signature"></i> Full Name</label>
                                        <input v-model="addadmin.fullname" type="text" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                                        <input v-model="addadmin.email" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label"><i class="fa-solid fa-circle-check"></i> Role</label>
                                        <select class="form-control form-control-sm" v-model="addadmin.role">
                                            <option value="super admin">Super Admin</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="closeAdd" @click="closeModelAccount">Close</button>
                                    <button type="button" class="btn btn-outline-primary" @click="addAccount">Add Account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Model Add Admin -->
                </div>

                <div id="bodytable">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Account</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Role</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody v-for="(ad,index) in admins" :key="index">
                            <tr>
                                <th scope="row">{{(pageN-1)*5+index+1}}</th>
                                <td>
                                    <div class="cell1">
                                        <div>
                                            <img src="../../assets/avatar.png" v-if="ad.url_img==null">
                                            <img :src="domain+'/'+ad.url_img" v-if="ad.url_img!=null">
                                        </div>
                                        <div>
                                            <p>{{ad.fullname}}</p>
                                            <p style="color:gray">{{ad.username}}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ad.email}}</td>
                                <td>{{ad.phone}}</td>
                                <td>
                                    <select class="form-control form-control-sm" v-model="ad.role">
                                        <option value="super admin">Super Admin</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </td>
                                <td style=""><button type="button" class="btn btn-outline-primary" @click="editRole(ad.id,ad.role)">Save</button></td>
                                <td style=""><button type="button" class="btn btn-outline-danger" @click="openModel(ad.id)" data-toggle="modal" data-target="#exampleModalDelete">Delete</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <div id="divpaginate">
                        <paginate class="pag" id="nvm"
                            :page-count="Math.ceil(this.quantity/5)"
                            :page-range="3"
                            :margin-pages="2"
                            :click-handler="clickCallback"
                            :initial-page="this.pageN"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'"
                            :page-class="'page-item'">
                        </paginate>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Delete Admin -->
        <div class="modal fade" id="exampleModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Are you sure you want to delete this account ? <br>
                            This account will be permanently deleted from the system !
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="closeDelete" id="closedl">Close</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteAdmin">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Model Delete Admin -->

        <Notification></Notification>

    </div>
</template>

<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'
import Paginate from 'vuejs-paginate-next';

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"ManagementAdmin",
    components:{
        Notification,
        paginate: Paginate,
        ParticleVue32
    },
    setup() {
      document.title = "Meta Shop - Management Admin";
    },
    data(){
        return {
            url_img:'',
            quantity:null,
            searchad:'',
            pageN:1,
            admins:null,
            admin:{
                id:null,
                fullname:'',
                username:'',
                email: '',
                phone: '',
                date_of_birth:null,
                url_img:null,
                gender:null,
                address:'',
                role:'',
                access_token:'',
                refreshToken:'',
                created_at:null,
                updated_at:null,
                email_verified_at:null,
            },
            domain:config.API_URL,
            idDelete:null,
            addadmin:{
                rolelogin:'',
                fullname:'',
                email:'',
                role:'admin'
            },
            err:{
                fullname:[],
                email:[],
                password:[]
            },
        }
    },
    mounted(){

        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('page')) {
            this.pageN = urlParams.get('page');
            if(this.pageN == 0) this.pageN = 1;
            // alert(this.pageN);
        }
        else {
            this.pageN = 1;
        }

        // Nếu như có biến search 
        if(urlParams.has('search')) {
            this.searchad = urlParams.get('search');
            this.pageN = 1;
        }

        BaseRequest.get('api/admin/all-admin?idlogin='+this.admin.id+'&rolelogin='+this.admin.role+'&page='+this.pageN+'&search='+this.searchad)
        .then( (data) =>{
            // console.log(data);
            this.quantity = data.quantity;
            this.admins = data.user.data ;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get All Admin Success !');

            // setTimeout(()=>{
            //     window.location=window.location.href;
            // }, 1500);
        }) 
        .catch(error=>{
            // console.log(error);
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.response.data.message);

            // Nếu là admin thì không vào được quản trị và không get được thì cho về trang chủ 
            setTimeout(()=>{
                this.$router.push({name:'DashboardAdmin'}); 
            }, 1500);

        })
    },

    methods:{
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spadministrator:function(){
            this.$router.push({name:'ManagementAdmin'});
            this.searchad='';
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        editRole:function(id,role){
            var editRole = {
                rolelogin:this.admin.role,
                id:id,
                role:role
            }
            BaseRequest.patch('api/admin/edit-role',editRole)
            .then((data)=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                // console.log(data);
            })
            .catch(()=>{
                // console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Edit Role Admin False !');
            })
        },
        openModel:function(id){
            this.idDelete = id;
        },
        closeDelete:function(){
            this.idDelete = null;
            // alert(this.idDelete);
        },
        deleteAdmin:function(){
            // alert(this.idDelete);
            var closedl = window.document.getElementById('closedl');

            BaseRequest.delete('api/admin/'+this.idDelete+'?rolelogin='+this.admin.role)
            .then((data)=>{
                // console.log(data);
                closedl.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
            .catch(()=>{
                // console.log(error);
                closedl.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Delete Admin False !');
                // setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
        },
        closeModelAccount:function(){
            // console.log(this.addadmin);
            this.addadmin.fullname = '';
            this.addadmin.email = '';
            this.addadmin.password = '';
            this.addadmin.role = 'admin';
            // console.log(this.addAdmin);
        },
        addAccount:function(){
            var closeAdd = window.document.getElementById('closeAdd');
            this.addadmin.rolelogin = this.admin.role;
            BaseRequest.post('api/admin/register',this.addadmin)
            .then((data)=>{
                // console.log(data);
                closeAdd.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
            .catch((error)=>{
                // console.log(error);
                closeAdd.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Add Admin False !');
                this.err = error.response.data;
                var error2 = this.err;
                if(error2.fullname) this.inError(error2.fullname);
                if(error2.username) this.inError(error2.username);
                if(error2.email) this.inError(error2.email);
                // setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
        clickCallback:function(pageNum){
            BaseRequest.get('api/admin/all-admin?idlogin='+this.admin.id+'&rolelogin='+this.admin.role+'&page='+pageNum+'&search='+this.searchad)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                this.pageN = pageNum;
                // let urlParams = new URLSearchParams(window.location.search);
                // urlParams.set('page', this.pageN);
                // window.location.search.set('page',this.pageN);
                this.admins = data.user.data ;
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Get All Admin Success !');

                // setTimeout(()=>{
                //     window.location=window.location.href;
                // }, 1500);
            }) 
            .catch(error=>{
                // console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);

                // Nếu là admin thì không vào được quản trị và không get được thì cho về trang chủ 
                setTimeout(()=>{
                    this.$router.push({name:'DashboardAdmin'}); 
                }, 1500);
            })
        },
        clicksearch:function(){
            window.location = window.location.pathname+"?search="+this.searchad;
        }
    },
    watch:{
        searchad:function(){
            // console.log(this.searchad);
            this.pageN = 1 ;
            BaseRequest.get('api/admin/all-admin?idlogin='+this.admin.id+'&rolelogin='+this.admin.role+'&page='+this.pageN+'&search='+this.searchad)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                // let urlParams = new URLSearchParams(window.location.search);
                // urlParams.set('page', this.pageN);
                // window.location.search.set('page',this.pageN);
                this.admins = data.user.data ;
                // const { emitEvent } = useEventBus();
                // emitEvent('eventSuccess','Get All Admin Success !');

                // setTimeout(()=>{
                //     window.location=window.location.href;
                // }, 1500);
            }) 
            .catch(error=>{
                // console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);

                // Nếu là admin thì không vào được quản trị và không get được thì cho về trang chủ 
                setTimeout(()=>{
                    this.$router.push({name:'DashboardAdmin'}); 
                }, 1500);
            })
        }
    }
}
</script>

<style scoped>
#administrator{
    background-color: #F2F4F6;
    padding: 16px 30px;
    height: 800px;
    position: relative;
}

#search {
    display: flex;
}
#pr {
    line-height: 100%;
    color: #0085FF;
    align-items: center;
    display: flex;
    cursor: pointer;
    margin-left: 20px;
}
#pr img{
    object-fit: cover;
    width: 40px;
    height: 40px;
    border-radius: 20px;
}
#home {
    color: #0085FF;
    cursor: pointer;
}
/* #home:hover{
    text-decoration: underline;
} */
#spadministrator{
    color: #3a9efb;
    cursor: pointer;
}
/* #spadministrator:hover{
    text-decoration: underline;
} */
.under{
    position: relative;
    padding: 0px 0px;
}
.under::after{
    content: ' ';
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 0;
    height: 2px;
    background:#0085FF;
    transition: width 0.3s;
}
.under:hover::after {
    width: 100%;
    transition: width 0.3s;
}
#big {
    position: relative;
    background-color: white;
    border-radius: 10px;
    align-items: center;
    box-shadow: 0px 10px 10px -10px #0085FF;
    background-color: #0085FF;
    height: 80px;
    margin-top: 50px;
}
#head {
    position: absolute;
    top: -50px;
    display: flex;
    justify-content: space-between;
    padding: 10px 40px;
    margin: 10px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px white;
    border-radius: 10px;
    align-items: center;
    width: 93%;
}

#table {
    top: 40px;
    position: absolute;
    justify-content: space-between;
    padding: 0px 0px;
    margin: 10px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    border-radius: 10px;
    align-items: center;
    height: 550px;
    width: 93%;
    font-size: 13px;
}
#bodytable .btn {
    font-size: 12px;
    justify-content: center;
    margin-left: 0px;
    margin-right: 0px;
}
#table .select{
    font-size: 12px;
    cursor: pointer;
}
#toptable {
    display: flex;
    padding: 30px 20px 10px 20px;
    margin-bottom: 30px;
    justify-content: space-between;
    align-items: center;
}

/* btn add admin */
#add_button{
    position:absolute;
    top: 0px;
    right: 0px;
}
#add_button button{
    font-size: 22px;
    display: flex;
    line-height: 100%;
    align-items: center;
    border-radius: 15px;
}
.btn-pers {
  position: relative;
  /* left: 50%; */
  padding: 16px 16px;
  /* font-size: 12px; */
  text-transform: uppercase;
  /* letter-spacing: 2.5px; */
  font-weight: 700;
  /* color: #000; */
  color: #0085FF;
  background-color: #fff;
  border: none;
  border-radius: 45px;
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease 0s;
  cursor: pointer;
  outline: none;
  transform: translateX(-50%);
}

.btn-pers:hover {
  background-color: #0085FF;
  box-shadow: 0px 15px 20px rgba(46, 138, 229, 0.4);
  color: #fff;
  transform: translate(-50%, -7px);
}

.btn-pers:active {
  transform: translate(-50%, -1px);
}



/* Table */
.btn {
    transition: all 1s ease;
}
.cell1 {
    display: flex;
    align-items: center;
    align-content: center;
}
.cell1 img {
    width: 30px;
    object-fit: cover;
    width: 40px;
    height: 40px;
    border-radius: 20px;
    margin-right: 6px;
}
#exampleModalDelete .btn{
    transition: all 1s ease;
}

#exampleModalAddAdmin .btn {
    transition: all 1s ease;
}


#divpaginate{
    display: flex;
    width: 100%;
    justify-content: center;
    position:absolute; /* giúp cho bao nhiêu dòng nó cũng không bị nhảy lên hay nhảy xuống */
    bottom: 20px; /* nếu ít dòng thì nó nhảy lên , nếu nhiều dòng thì nó thụt xuống nên mình cho nó cố định luôn */
}

#validationTooltipUsernamePrepend{
    cursor: pointer;
}
#validationTooltipUsernamePrepend:hover{
    color: #0085FF;
}


</style>
