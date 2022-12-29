<template>
    <div id="administrator">
        <ParticleVue32/>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spcustomer" id="spadministrator">Customer</span></div>
                    <div style="font-weight: bold">Customer</div>
                </div>
                <div id="search">
                    <div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span @click="clicksearch" class="input-group-text" id="validationTooltipUsernamePrepend"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                            <input v-model="searchad" style="width:400px;border-top-right-radius: 6px;border-bottom-right-radius: 6px;" type="text" class="form-control" id="validationTooltipUsername" placeholder="Search Information Customer" aria-describedby="validationTooltipUsernamePrepend" required>
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
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-users-gear"></i> Customer Table</div>
                </div>

                <div id="bodytable">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Account</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody v-for="(cus,index) in customers" :key="index">
                            <tr>
                                <th scope="row">{{(pageN-1)*5+index+1}}</th>
                                <td>
                                    <div class="cell1">
                                        <div>
                                            <img src="../../assets/avatar.png" v-if="cus.url_img==null">
                                            <img :src="domain+'/'+cus.url_img" v-if="cus.url_img!=null">
                                        </div>
                                        <div>
                                            <p>{{cus.fullname}}</p>
                                            <p style="color:gray">{{cus.username}}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{cus.email}}</td>
                                <td>{{cus.phone}}</td>
                                <td style="padding-left: 0px;">
                                    <div class="big-toggle" @click="cus.status=!cus.status;editStatus(cus.id,cus.status)">
                                        <div class="button2 r button-6">
                                            <input :checked="!cus.status" type="checkbox" class="checkbox" />
                                            <div class="knobs"></div>
                                            <div class="layer"></div>
                                        </div>
                                    </div>
                                </td>
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
    name:"CustomerAdmin",
    components:{
        Notification,
        paginate: Paginate,
        ParticleVue32
    },
    setup() {
      document.title = "Meta Shop - Customer";
    },
    data(){
        return {
            url_img:'',
            quantity:null,
            searchad:'',
            pageN:1,
            customers:null,
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
        }
        else {
            this.pageN = 1;
        }

        // Nếu như có biến search 
        if(urlParams.has('search')) {
            this.searchad = urlParams.get('search');
            this.pageN = 1;
        }

        BaseRequest.get('api/admin/all-user?page='+this.pageN+'&search='+this.searchad)
        .then( (data) =>{
            // console.log(data);
            this.quantity = data.quantity;
            this.customers = data.user.data ;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get All Customer Success !');
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
        spcustomer:function(){
            this.$router.push({name:'CustomerAdmin'});
            this.searchad='';
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        editStatus:function(id,status){
            var customerStatus = {
                id:id,
                status:status
            }
            BaseRequest.patch('api/admin/edit-status',customerStatus)
            .then((data)=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                // console.log(data);
            })
            .catch(()=>{
                // console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Edit Status False !');
            })
        },
        clickCallback:function(pageNum){
            BaseRequest.get('api/admin/all-user?page='+pageNum+'&search='+this.searchad)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                this.pageN = pageNum;
                this.customers = data.user.data ;
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Get Customer Success !');
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
            this.pageN = 1 ;
            BaseRequest.get('api/admin/all-user?page='+this.pageN+'&search='+this.searchad)
            .then( (data) =>{
                this.quantity = data.quantity;
                this.customers = data.user.data ;
            }) 
            .catch(error=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);

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
/* #spadministrator:hover {
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

/* toggle button */
.knobs,
.layer {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.button2 {
  position: relative;
  top: 50%;
  width: 74px;
  height: 36px;
  margin: -20px auto 0 auto;
  overflow: hidden;
}

.button2.r,
.button2.r .layer {
  border-radius: 100px;
}

.button2.b2 {
  border-radius: 2px;
}

.checkbox {
  position: relative;
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
  opacity: 0;
  cursor: pointer;
  z-index: 3;
}

.knobs {
  z-index: 2;
}

.layer {
  width: 100%;
  background-color: #ebf7fc;
  transition: 0.3s ease all;
  z-index: 1;
}



/* Button 6 */
.button-6 {
  overflow: visible;
}

.button-6 .knobs:before {
  content: "YES";
  position: absolute;
  align-content: center;
  top: 4px;
  left: 4px;
  width: 28px;
  height: 28px;
  color: #fff;
  font-size: 10px;
  font-weight: bold;
  text-align: center;
  line-height: 1;
  padding: 9px 4px;
  background-color: #03a9f4;
  border-radius: 50%;
}

.button-6 .layer,
.button-6 .knobs,
.button-6 .knobs:before {
  transform: rotateZ(0);
  transition: 0.4s cubic-bezier(0.18, 0.89, 0.35, 1.15) all;
}

.button-6 .checkbox:checked + .knobs {
  transform: rotateZ(-180deg);
}

.button-6 .checkbox:checked + .knobs:before {
  content: "NO";
  background-color: #f44336;
  transform: rotateZ(180deg);
}

.button-6 .checkbox:checked ~ .layer {
  background-color: #fcebeb;
  transform: rotateZ(180deg);
}
.big-toggle {
    position: relative;
    padding-top:20px;
    display: flex;
    width: 100px;
    height: 100%;
    align-content: center;
    align-items: center;
}


</style>
