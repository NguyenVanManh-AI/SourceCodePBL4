<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spprintpdfadmin" id="spprintpdfadmin">Print PDF</span></div>
                    <div style="font-weight: bold" class="ef">Print PDF</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>

            <div id="table">
                <div id="content_print">
                    <br><br>
                    <div id="title">Order details</div>
                    <div id="title2"><i class="fa-solid fa-user-shield"></i> EXPORTER INFORMATION</div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-signature"></i> File Exporter</span>
                        <span class="col-6">{{admin.fullname}}</span>
                    </div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-envelope-circle-check"></i> Email</span>
                        <span class="col-6">{{admin.email}}</span>
                    </div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-phone"></i> Phone</span>
                        <span class="col-6">{{admin.phone}}</span>
                    </div>
                    <br><hr><br>
                    <div id="title3"><i class="fa-solid fa-bars"></i> ORDER DETAILS</div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-barcode"></i> Order Code</span>
                        <span class="col-6">{{customer_order.hex_id}}</span>
                    </div>

                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-calendar-day"></i> Order date</span>
                        <span class="col-6">{{customer_order.order_time}}</span>
                    </div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-regular fa-calendar-check"></i> Confirm date</span>
                        <span class="col-6">{{customer_order.confirm_time}}</span>
                    </div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-calendar-check"></i> Ship date</span>
                        <span class="col-6">{{customer_order.ship_time}}</span>
                    </div>
                    <div class="row infor">
                        <span class="col-2"><i class="fa-solid fa-circle-check"></i> Delivered</span>
                        <span class="col-6">{{customer_order.completed_time}}</span>
                    </div>
                    <br><hr><br>
                    <div id="title4"><i class="fa-brands fa-dropbox"></i> LIST PRODUCTS</div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pr,index) in order_details" :key="index" :class="{bgcl:(index%2==0)}">
                                <th scope="row">{{index+1}}</th>
                                <td class="col-3" style="color:green">{{pr.product_name}}</td>
                                <td class="col-3 pr_img"><img :src="API_URL+'/'+pr.image_path"></td>
                                <td class="col-1" style="color:red">{{new Intl.NumberFormat().format(pr.quantity)}}</td>
                                <td class="col-2" style="color:red">$ {{new Intl.NumberFormat().format(pr.price)}}</td>
                                <td class="col-2" style="color:red">$ {{new Intl.NumberFormat().format(pr.price*pr.quantity)}}</td>
                            </tr>
                        </tbody>
                    </table>  
                    <div class="row money">
                        <span class="col-3"><i class="fa-solid fa-boxes-stacked"></i> Total Product</span>
                        <span class="col-6">{{new Intl.NumberFormat().format(numberPr)}} </span>
                    </div>
                    <div class="row money2">
                        <span class="col-3"><i class="fa-solid fa-money-bill-wave"></i> Total payment amount</span>
                        <span class="col-6">$ {{new Intl.NumberFormat().format(total)}}</span>
                    </div> 
                    <div class="row" style="font-size: 12px;margin-left: 1px;color: gray;">
                        Form No. A1-02/NS<br>
                        (Issued together with Circular No. 254/2022/TT - BTC of the Ministry of Finance)
                    </div>
                    <div class="row infor">
                        <span class="col-12">File export date <i class="fa-solid fa-calendar-check"></i> : {{date_now}}</span>
                    </div>

                    <div id="logo" >
                        <div id="inner">
                            <img src="../../assets/logo.png">
                        </div>
                        <div id="txt">Meta Shop</div>
                    </div>   
                    
                    <div id="logo2" >
                        <div id="inner2">
                            <img src="../../assets/logo.png">
                        </div>
                        <div id="txt2">Meta Shop</div>
                    </div> 

                </div>
                <div class="dt1">
                    <button type="submit" class="mt-4 btn-pers" v-print="'#content_print'"><i class="fa-solid fa-file-arrow-down"></i> Export PDF </button>
                </div>  
            </div>
            <button></button>
        </div>
        <Notification></Notification>
    </div>
</template>
<script>

import config from '../../config.js';
import Notification from './Notification'
import useEventBus from '../../composables/useEventBus'
import BaseRequest from '../../restful/admin/core/BaseRequest';

export default {
    name: "AdminOrderPrintPDF",
    components: {
        Notification
    },
    data(){
        return{
            uriOrderDetails:'',
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
            order_details:null,
            total:0,
            API_URL : '',
            numberPr:0,
            customer_order:{
                id:null,
                customer_id:null,
                hex_id:null,
                customer_name:null,
                recipient_name:null,
                phone_number:null,
                address:null,
                order_status:null,
                order_time:null,
                confirm_time:null,
                ship_time:null,
                completed_time:null,
                shipping_fee:null,
            },
            domain:config.API_URL,
            url_img:'',
            date_now:null,

        }
    },
    setup() {
        document.title = "Meta Shop - Admin Order Print PDF";
    },
    mounted(){
        this.uriOrderDetails = location.pathname.substring(13);
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.API_URL = config.API_URL; 
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        const { emitEvent } = useEventBus();
        BaseRequest.get('api/admin-order/print?hex_id='+this.uriOrderDetails)
        .then( (data) =>{
            this.order_details = data.order_details;
            this.total = data.total;
            this.numberPr = this.order_details.length;
            this.customer_order = data.customer_order;
            this.date_now = data.date_now;
            emitEvent('eventUserSuccess','Get all order products success !');
        }) 
        .catch(()=>{
            emitEvent('eventUserError','Get all order products failse !');
        })

    },
    methods: {
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spprintpdfadmin:function(){
            window.location = window.location.href;
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
    }

}
</script>
<style scoped>

/* logo */
#logo {
    position: absolute;
    /* top:190px; */
    top:260px;
    left:340px;
    transform: rotate(45deg);
}
#inner {
    border: 2px solid red;
    width: 120px;
    border-radius: 60px;
    padding: 20px;
}
#txt {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: 10px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 2.5px;
    font-weight: 700;
    text-decoration: underline;
    color: #0085FF;
}
#logo2 {
    justify-content: center;
    width: 100%;
    margin-top: -100px;
    /* margin-left: 70%; */
    padding-left: 70%;
}
#inner2 {
    border: 2px solid red;
    width: 120px;
    border-radius: 60px;
    padding: 20px;
}
#txt2 {

    margin-top: 10px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 2.5px;
    font-weight: 700;
    text-decoration: underline;
    color: #0085FF;
}



/* button */
.dt1 input {
    color: #0085FF;
}
.dt1 {
    width: 100%;
    align-content: center;
    display: flex;
    margin-bottom: 20px;
    justify-content: center;
    padding-left: 90px;
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
  padding: 16px 36px;
  /* font-size: 12px; */
  text-transform: uppercase;
  /* letter-spacing: 2.5px; */
  font-weight: 700;
  /* color: #000; */
  color: #FF6233;
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









.bgcl {
    background-color: rgb(240, 240, 240);
}

#title {
    width: 100%;
    display: flex;
    justify-content: center;
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 3px;
    font-weight: bold;
    font-size: 20px;
    /* padding-top: 30px; */
    /* lỗi table không có border có thể là do padding của title nên nếu lỗi thì xóa đi */
}

#title2,#title3,#title4 {
    text-transform: uppercase;
    font-weight: bold;
    font-weight: bold;
    font-size: 16px;
    color: gray;
}
#title2 {
    padding-top: 20px;
}
#title3 {
    margin-top: 0px;
}
.infor span {
    font-weight: bold;
    font-size: 14px;
    color: rgb(76, 72, 72);
}
.infor span:nth-child(1) {
    letter-spacing: 1px;
}

.money,.money2 {
    font-weight: bold;
    font-size: 14px;
    display: flex;
    align-content: center;
    align-items: center;
}
.money span:nth-child(2){
    color: red;
    font-size: 20px;
}
.money2 span:nth-child(2){
    color: green;
    font-size: 20px;
}


#main {
    width: 100%;
    background-color: white;
    padding: 20px;
    min-height: 440px;
    margin-bottom: 20px;
}
.pr_infor {
  display: flex;
  align-content: center;
  align-items: center;
}
.pr_border {
  border-bottom: 2px solid #F0F2F5;
}
.pr_img {
    padding: 10px;
}
.pr_img img {
  width: 20%;
  height: 10%;
  object-fit: contain;
}
.pr_name span{
  color: #0085FF;
  font-weight: bold;
  font-size: 20px;
}
.pr_num {
  /* color: #218838; */
  color: red;
  font-weight: bold;
}
.product{
    text-transform: uppercase;
    letter-spacing: 6px;
    padding-right: 6px;
}

.total {
  font-weight: bold;
  display: flex;
  align-content: center;
  align-items: center;
}
.total .span2 {
  color: #218838;
  font-size: 20px;
}
.product2{
    text-transform: uppercase;
    letter-spacing: 3px;
    color: black;
}


#administrator{
    background-color: #F2F4F6;
    padding: 0px 30px;
    position: relative;
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

#spprintpdfadmin{
    color: #3a9efb;
    cursor: pointer;
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
.ef {
    -webkit-animation: pulse 2s cubic-bezier(.4,0,.6,1) infinite!important;
    animation: pulse 2s cubic-bezier(.4,0,.6,1) infinite!important;
}
#head {
    position: absolute;
    top: -50px;
    display: flex;
    justify-content: space-between;
    padding: 10px 40px;
    margin: 10px 0px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px white;
    border-radius: 10px;
    align-items: center;
    width: 100%;
}
#table {
    top: 40px;
    position: absolute;
    justify-content: space-between;
    padding: 0px 30px;
    margin: 10px 0px;
    background-color: white;
    /* box-shadow: 0px 10px 10px -10px gray; */
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    border-radius: 10px;
    align-items: center;
    width: 100%;
    min-height: 440px;
    font-size: 13px;
    /* border: 10px solid red; */
}

</style>