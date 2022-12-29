<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="sporderadmin" id="sporderadmin">Order</span></div>
                    <div style="font-weight: bold" class="ef">Order</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>

            <div id="table">
                <div id="header">
                    <li @click="waitForConfirmation" :class="{border_bt:border_bts[0],border_bt2:!border_bts[0]}"><i class="fa-brands fa-shopify mr-1"></i> Wait for confirmation</li>
                    <li @click="waitingForShipping" :class="{border_bt:border_bts[1],border_bt2:!border_bts[1]}"><i class="fa-regular fa-circle-check mr-1"></i> Waiting for shipping</li>
                    <li @click="delivering" :class="{border_bt:border_bts[2],border_bt2:!border_bts[2]}"><i class="fa-solid fa-truck-fast mr-1"></i> Delivering</li>
                    <li @click="delivered" :class="{border_bt:border_bts[3],border_bt2:!border_bts[3]}"><i class="fa-solid fa-house-circle-check mr-1"></i> Delivered</li>
                </div>
                <div id="content">
                    <router-view></router-view>
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

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"OrderAdmin",
    components:{
        Notification,
        ParticleVue32
    },
    setup() {
        document.title = "Meta Shop - Order"
    },
    data(){
        return {
            url_img:'',
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
            border_bts:[false,false,false,false],
        }
    },
    mounted(){
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        const { onEvent } = useEventBus()
        onEvent('eventAdminWaitForConfirmation',()=>{
            this.border_bts = [false,false,false,false],
            this.border_bts[0] = true;
        })

        var _pathname = window.location.pathname;
        if(_pathname.includes('confirmation')) this.border_bts[0] = true;
        if(_pathname.includes('shipping')) this.border_bts[1] = true;
        if(_pathname.includes('delivering')) this.border_bts[2] = true;
        if(_pathname.includes('delivered')) this.border_bts[3] = true;
    },
    methods:{
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        sporderadmin:function(){
            this.$router.push({name:'AdminWaitForConfirmation'});
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },

        waitForConfirmation:function(){
            this.$router.push({name:'AdminWaitForConfirmation'}); 
            this.reset_bt(0);
        },
        waitingForShipping:function(){
            this.$router.push({name:'AdminWaitingForShipping'}); 
            this.reset_bt(1);
        },
        delivering:function(){
            this.$router.push({name:'AdminDeliveringComp'}); 
            this.reset_bt(2);
        },
        delivered:function(){
            this.$router.push({name:'AdminDeliveredComp'}); 
            this.reset_bt(3);
        },
        reset_bt:function(index){
            this.border_bts = [false,false,false,false];
            this.border_bts[index] = true;
        }
        
    },
    watch:{
        searchad:function(){
            // console.log(this.searchad);
            this.pageN = 1 ;
            BaseRequest.post('api/categorys?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                // let urlParams = new URLSearchParams(window.location.search);
                // urlParams.set('page', this.pageN);
                // window.location.search.set('page',this.pageN);
                this.categorys = data.category.data ;
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
            })
        }
    }
}
</script>

<style scoped>
*{
    list-style: none;
}

#header{
    display: flex;
    width: 100%;
    height: 50px;
    align-content: center;
    align-items: center;
    /* border: 1px solid gray; */
    border-radius: 3px;
    background-color: white;
    justify-content: space-between;
    margin-bottom: 10px;
}
#header li {
    width: 25%;
    height: 100%;
    font-weight: bold;
    font-size: 16px;
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.border_bt {
    border-bottom: 3px solid #218838; 
    /* red */
    color: #218838;
}
.border_bt2 {
    border-bottom: 1px solid gray; 
}
#content{
    background-color: white;
    width: 100%;
    min-height: 500px;
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

#sporderadmin{
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
