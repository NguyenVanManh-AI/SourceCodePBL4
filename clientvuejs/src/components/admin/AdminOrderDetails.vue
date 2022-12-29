<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="sporderdetailsadmin" id="sporderdetailsadmin">Order Details</span></div>
                    <div style="font-weight: bold" class="ef">Order Details</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>

            <div id="table">
                <div id="main">
                    <div class="row pb-6" style="font-weight:bold">
                        <div style="color:green" class="col-6 pl-12 product">Product</div>
                        <div class="col-2 product d-flex justify-content-end">Price($)</div>
                        <div class="col-2 product d-flex justify-content-end">Quantity</div>
                        <div class="col-2 product d-flex justify-content-end">Total($)</div>
                    </div>
                    <hr>
                    <div id="order">
                        <div class="row pr_border" v-for="(pr,index) in order_details" :key="index">
                            <div class="col-6 pr_infor">
                                <div class="pr_img col-4" >
                                    <img :src="API_URL+'/'+pr.image_path"> 
                                </div>
                                <div class="pr_name col-8">
                                    <span><i class="fa-solid fa-feather-pointed"></i> {{pr.product_name}}</span><br>
                                </div>
                            </div>
                            <div class="col-2 d-flex d-flex align-items-center pr_num justify-content-end">${{new Intl.NumberFormat().format(pr.price)}}</div>
                            <div class="col-2 d-flex d-flex align-items-center pr_num justify-content-end">{{new Intl.NumberFormat().format(pr.quantity)}}</div>
                            <div class="col-2 d-flex d-flex align-items-center pr_num justify-content-end">${{new Intl.NumberFormat().format(pr.quantity*pr.price)}}</div>
                        </div>
                    </div>
                    <div class="row pl-10 pb-3 pt-3 ">
                        <div class="col-4 d-flex justify-content-start align-items-center" style="color:gray;font-size:16px;">
                            <span><i class="fa-solid fa-calendar-check"></i> Order time {{order_time}}</span>
                        </div>
                        <div class="col-8 d-flex justify-content-end align-items-center total ">
                            <span class="product2"> Total payment </span>  (<span class="span2" style="margin: 0px 10px;">{{new Intl.NumberFormat().format(numberPr)}}</span> <span class="product2" > product </span>) : <span class="span2" style="margin-left:6px">${{new Intl.NumberFormat().format(total)}}</span>
                        </div>
                    </div>
                </div>
            </div>
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
    name: "AdminOrderDetails",
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
            order_time:'',
            domain:config.API_URL,
            url_img:'',

        }
    },
    setup() {
        document.title = "Meta Shop - Admin Order Details";
    },
    mounted(){
        this.uriOrderDetails = location.pathname.substring(21);
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.API_URL = config.API_URL; 
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        const { emitEvent } = useEventBus();
        BaseRequest.get('api/admin-order/details?hex_id='+this.uriOrderDetails)
        .then( (data) =>{
            this.order_details = data.order_details;
            this.total = data.total;
            this.numberPr = this.order_details.length;
            this.order_time = data.order_time;
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
        sporderdetailsadmin:function(){
            window.location = window.location.href;
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
    }

}
</script>
<style scoped>
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
  width: 80%;
  height: 80%;
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

#sporderdetailsadmin{
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