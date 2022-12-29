<template>
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

        <Notification></Notification>
    </div>
</template>
<script>

import config from '../../../config.js';
import Notification from './../Notification'
import useEventBus from '../../../composables/useEventBus'
import BaseRequest from '../../../restful/user/core/BaseRequest';

export default {
    name: "OrderDetails",
    components: {
        Notification
    },
    data(){
        return{
            uriOrderDetails:'',
            user:{
                id:null,
                fullname:'',
                username:'',
                email: '',
                phone: '',
                google_id:null,
                date_of_birth:null,
                url_img:null,
                gender:null,
                address:'',
                status:null,
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

        }
    },
    setup() {
        document.title = "Meta Shop - Order Details";
    },
    mounted(){
        this.uriOrderDetails = location.pathname.substring(28);
        this.user = JSON.parse(window.localStorage.getItem('user'));
        this.API_URL = config.API_URL; 

        const { emitEvent } = useEventBus();
        BaseRequest.get('api/customer-order/details?hex_id='+this.uriOrderDetails)
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
    
    }

}
</script>
<style scoped>
#main {
    width: 100%;
    background-color: white;
    padding: 20px;
    min-height: 400px;
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
</style>