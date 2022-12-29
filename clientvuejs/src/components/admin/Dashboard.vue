<template>
    <div id="big">
        <ParticleVue32></ParticleVue32>
        <div id="main">
            <div id="main_title"><span><i class="fa-solid fa-gear"></i></span> <span>Overview of the data of the METASHOP sales system</span></div>
            <div class="dash" @click="click1">
                <p class="dash2"><span class="icon sp1"><i class="fa-solid fa-user-shield"></i></span> <span class="title">Admin account number </span></p>
                <p class="number sp11">{{infor_dashboard.alladmin}}</p>
            </div>
            
            <div class="dash" @click="click1">
                <p class="dash2"><span class="icon sp2"><i class="fa-solid fa-user-check"></i></span> <span class="title">Super admin</span></p>
                <p class="number sp22">{{infor_dashboard.alladmin - infor_dashboard.admin}}</p>
            </div>

            <div class="dash" @click="click2">
                <p class="dash2"><span class="icon sp3"><i class="fa-solid fa-people-carry-box"></i></span> <span class="title">Provider </span></p>
                <p class="number sp33">{{infor_dashboard.provider}}</p>
            </div>

            <div class="dash" @click="click3">
                <p class="dash2"><span class="icon sp4"><i class="fa-solid fa-user"></i></span> <span class="title">User account </span></p>
                <p class="number sp44">{{infor_dashboard.allcustomer}}</p>
            </div>

            <div class="dash" @click="click3">
                <p class="dash2"><span class="icon sp5"><i class="fa-solid fa-user-lock"></i></span> <span class="title">User block number</span></p>
                <p class="number sp55">{{infor_dashboard.customer_block}}</p>
            </div>

            <div class="dash" @click="click4">
                <p class="dash2"><span class="icon sp6"><i class="fa-solid fa-box-open"></i></span> <span class="title">Product</span></p>
                <p class="number sp66">{{infor_dashboard.product}}</p>
            </div>

            <div class="dash" @click="click5" style="margin-right:0px">
                <p class="dash2"><span class="icon sp7"><i class="fa-solid fa-bars-staggered"></i></span> <span class="title">Category</span></p>
                <p class="number sp77">{{infor_dashboard.category}}</p>
            </div>

            <div class="dash" @click="click6">
                <p class="dash2"><span class="icon sp8"><i class="fa-solid fa-dolly"></i></span> <span class="title">Import</span></p>
                <p class="number sp88">{{infor_dashboard.imports}}</p>
            </div>

            <div class="dash" @click="click7">
                <p class="dash2"><span class="icon sp9"><i class="fa-solid fa-cart-shopping"></i></span> <span class="title">Total Order</span></p>
                <p class="number sp99">{{infor_dashboard.all_customer_order}}</p>
            </div>

            <div class="dash" @click="click7">
                <p class="dash2"><span class="icon sp10"><i class="fa-brands fa-shopify mr-1"></i></span> <span class="title">Wait confirm</span></p>
                <p class="number sp101">{{infor_dashboard.WaitForConfirmation}}</p>
            </div>

            <div class="dash" @click="click8">
                <p class="dash2"><span class="icon sp11a"><i class="fa-regular fa-circle-check mr-1"></i></span> <span class="title">Wait ship</span></p>
                <p class="number sp111">{{infor_dashboard.WaitingForShipping}}</p>
            </div>

            <div class="dash" @click="click9">
                <p class="dash2"><span class="icon sp12"><i class="fa-solid fa-truck-fast mr-1"></i></span> <span class="title">Delivering</span></p>
                <p class="number sp121">{{infor_dashboard.Delivering}}</p>
            </div>

            <div class="dash" @click="click10">
                <p class="dash2"><span class="icon sp12a"><i class="fa-solid fa-house-circle-check mr-1"></i></span> <span class="title">Delivered</span></p>
                <p class="number sp12a1">{{infor_dashboard.OrderDelivered}}</p>
            </div>

            <div class="dash" @click="click7">
                <p class="dash2"><span class="icon sp14"><i class="fa-solid fa-trash"></i></span> <span class="title">Cancel</span></p>
                <p class="number sp141">{{infor_dashboard.OrderCancel}}</p>
            </div>

            <br><br><hr> 
            <div id="title-chart"><i class="fa-solid fa-database"></i> Statistical chart of the total number of orders of the system</div>
            <div class="col-10 mx-auto" id="bart-chart">
                <BarChar></BarChar>
            </div>

        </div>
        <Notification></Notification>
    </div>
</template>

<script>
import ParticleVue32 from "./particle/ParticleVue32.vue";
import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus';
import Notification from './Notification';
import BarChar from './admin_chart/BarChart.vue';
// import Chart from 'chart.js/auto';
// import config from '../../config.js'
// import Paginate from 'vuejs-paginate-next';

export default {
    name: "DashboardAdmin",
    components: {
        ParticleVue32,
        Notification,
        BarChar
    },
    setup() {
        document.title = "Meta Shop - Dashboard";
    },
    data(){
        return {
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
            infor_dashboard: {
                alladmin : 0,
                admin : 0,
                allcustomer : 0,
                customer_block : 0,
                product : 0,
                provider : 0,
                imports : 0,
                category : 0,
                all_customer_order : 0,
                WaitForConfirmation : 0,
                WaitingForShipping : 0,
                Delivering : 0,
                OrderDelivered : 0,
                OrderCancel : 0
            },
        }
    },
    methods: {
        click1:function(){this.$router.push({name:'ManagementAdmin'});},
        click2:function(){this.$router.push({name:'ProviderAdmin'});},
        click3:function(){this.$router.push({name:'CustomerAdmin'});},
        click4:function(){this.$router.push({name:'ProductAdmin'});},
        click5:function(){this.$router.push({name:'CategoryAdmin'});},
        click6:function(){this.$router.push({name:'WareHouse'});},
        click7:function(){this.$router.push({name:'AdminWaitForConfirmation'});},
        click8:function(){this.$router.push({name:'AdminWaitingForShipping'});},
        click9:function(){this.$router.push({name:'AdminDeliveringComp'});},
        click10:function(){this.$router.push({name:'AdminDeliveredComp'});},
    },
    mounted(){

        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        // if(!window.localStorage.getItem('admin')){
        //     this.$router.push({name:"LoginAdmin"});
        // }

        BaseRequest.get('api/dashboard-admin/dashboard')
        .then( (data) =>{
            this.infor_dashboard = data.infor_dashboard ;

            var data_chart = [];
            data_chart[0] = data.infor_dashboard.all_customer_order;
            data_chart[1] = data.infor_dashboard.WaitForConfirmation;
            data_chart[2] = data.infor_dashboard.WaitingForShipping;
            data_chart[3] = data.infor_dashboard.Delivering;
            data_chart[4] = data.infor_dashboard.OrderDelivered;
            data_chart[5] = data.infor_dashboard.OrderCancel;

            const { emitEvent } = useEventBus();
            emitEvent('eventBarChart',data_chart);
            emitEvent('eventSuccess','Show Dashboard Success !');
        }) 
        .catch(()=>{
            const { emitEvent } = useEventBus();
            emitEvent('eventError','Show Dashboard Fails !');
        })
    }
}
</script>

<style scoped>

#bart-chart {
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;
}

#title-chart {
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
    font-size: 18px;
    font-weight: bold;
    color: #01a738;
    margin: 16px;
    text-transform: uppercase;
}
#title-chart i {
    margin: 0px 6px;
}

.sp1 {
    background-color: rgb(215, 29, 29);
}
.sp11 {
    color: rgb(215, 29, 29);
}

.sp2 {
    background-color: rgb(29, 215, 48);
}
.sp22 {
    color: rgb(29, 215, 48);
}

.sp3 {
    background-color: rgb(215, 153, 29);
}
.sp33 {
    color: rgb(215, 153, 29);
}

.sp4 {
    background-color: rgb(9, 181, 26);
}
.sp44 {
    color: rgb(9, 181, 26);
}

.sp5 {
    background-color: rgb(230, 44, 44);
}
.sp55 {
    color: rgb(230, 44, 44);
}

.sp6 {
    background-color: rgb(19, 98, 235);
}
.sp66 {
    color: rgb(19, 98, 235);
}

.sp7 {
    background-color: rgb(165, 213, 7);
}
.sp77 {
    color: rgb(165, 213, 7);
}

.sp8 {
    background-color: rgb(172, 7, 213);
}
.sp88 {
    color: rgb(172, 7, 213);
}

.sp9 {
    background-color: rgb(7, 213, 7);
}
.sp99 {
    color: rgb(7, 213, 7);
}

.sp10 {
    background-color: rgb(7, 175, 213);
}
.sp101 {
    color: rgb(7, 175, 213);
}

.sp11a {
    background-color: rgb(165, 213, 7);
}
.sp111 {
    color: rgb(165, 213, 7);
}

.sp12 {
    background-color: rgb(117, 213, 7);
}
.sp121 {
    color: rgb(117, 213, 7);
}

.sp12a {
    background-color: rgb(65, 213, 7);
}
.sp12a1 {
    color: rgb(65, 213, 7);
}

.sp14 {
    background-color: rgb(213, 24, 7);
}
.sp141 {
    color: rgb(213, 24, 7);
}


#big{
    background-color: #F2F4F6;
    padding: 16px 30px;
    height: 800px;
}
#main {
    padding: 10px 25px;
    margin: 10px 20px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    border-radius: 10px;
    align-items: center;
    position: relative;
    min-height: 530px;
}

#main_title {
    display: flex;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    color: #0085FF;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 10px;
}
#main_title i {
    margin: 3px;
}


.dash {
    display: inline-flex;
    align-content: center;
    justify-content: center;
    align-content: center;
    background-color: white;
    height: 60px;
    margin-right: 10px;
    margin-bottom: 10px;
    border-radius: 10px;
    padding: 10px 10px;
    cursor: pointer;
    transition: all 1s ease;
    box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;
}

.dash2 {
    display: flex;
    align-content: center;
    align-items: center;
}
.icon {
    display: inline-flex;
    width: 40px;
    height: 40px;
    line-height: 40px;
    justify-content: center;
    align-items: center;
    border-radius: 6px;
    color: white;
    font-size: 22px;
}
.title {
    font-size: 18px;
    font-weight: bold;
    margin:0px 6px;
    color: rgb(81, 80, 80);
    transition: all 1s ease;
}
.number {
    display: flex;
    align-content: center;
    font-size: 30px;
    font-weight: bold;
    align-items: center;
}

.dash:hover{
    background-color: black;
}
.dash:hover .title{
    color: white;
}
</style>