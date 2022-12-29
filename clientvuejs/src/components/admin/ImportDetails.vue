<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spwarehouseimport" id="spwarehouseimport">Warehouse Import</span>
                        / <span class="under" @click="spdetailsImport" id="spdetailsImport">Import Details</span>
                    </div>
                    <div style="font-weight: bold">Import Details</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>
            
            <div id="table">
                <div style="position: relative;">
                    <div id="toptable">
                        <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-cart-flatbed"></i> Import Details </div>
                    </div>
                    <div class="noprint">
                        <div style="color: gray;font-size: 1rem;padding-top: 10px;margin-bottom: 20px;"><i class='bx bxl-dropbox'></i> IMPORT INFORMATION</div>
                    </div>
                    <div id="bodytable">

                        <!-- <div class="row" style="margin-left: 1000px;margin-top: 40px;margin-bottom: 90px; font-size: 16px;">
                            Form No. A1-02/NS<br>
                            (Issued together with Circular No. 254/2022/TT - BTC of the Ministry of Finance)
                        </div>
                        <div class="row">
                            <label style="font-weight: bold;margin-left: 600px;font-size: 20px;text-transform: uppercase;">Goods Import Details Receipt</label>
                        </div> -->

                        <div class="row" style="margin-left: 1px;color: gray;">
                            <label style="font-weight: bold;font-size: 18px;text-transform: uppercase;">Goods Import Details Receipt</label>
                        </div>
                        <div class="row">
                            <label style="font-weight: bold;margin-left: 14px;">Importer</label>
                            <label style="font-weight: bold;margin-left: 92px;">: {{importer.importer_name}}</label>
                        </div>
                        <div class="row">
                            <label style="font-weight: bold;margin-left: 14px;">Provider</label>
                            <label style="font-weight: bold;margin-left: 96px;">: {{importer.provider_name}}</label>
                        </div>
                        <div class="row">
                            <label style="font-weight: bold;margin-left: 14px;">Tax ID Number</label>
                            <label style="font-weight: bold;margin-left: 48px;">: {{importer.provider_tax_id}}</label>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Tax</th>
                                    <th scope="col">Total Money</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(import_detail,index) in import_details" :key="index" :class="{bgcl:(index%2==0)}">
                                    <th scope="row">{{index+1}}</th>
                                    <td>{{import_detail.product_name}}</td>
                                    <td>{{new Intl.NumberFormat().format(import_detail.quantity)}}</td>
                                    <td>$ {{new Intl.NumberFormat().format(import_detail.price)}}</td>
                                    <td>{{import_detail.tax}} %</td>
                                    <td>$ {{new Intl.NumberFormat().format(sum_price[index])}}</td>
                                </tr>
                            </tbody>
                        </table>   
                        <div class="row">
                            <label style="font-weight: bold;margin-left: 14px;">Total Money</label>
                            <label style="font-weight: bold;margin-left: 72px;">: $ {{new Intl.NumberFormat().format(sum)}}</label>
                        </div>
                        <div class="row">
                            <label style="font-weight: bold;margin-left: 14px;">Import Date <i class="fa-solid fa-calendar-check"></i></label>
                            <label style="font-weight: bold;margin-left: 54px;">: {{importer.import_date}}</label>
                        </div>   
                        <div class="row" style="font-size: 12px;margin-left: 1px;color: gray;">
                            Form No. A1-02/NS<br>
                            (Issued together with Circular No. 254/2022/TT - BTC of the Ministry of Finance)
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
                </div>     
                <div class="dt1">
                    <button type="submit" class="mt-4 btn-pers" v-print="'#bodytable'"><i class="fa-solid fa-file-arrow-down"></i> Export PDF </button>
                </div>               
            </div>                    
            <p style="position: absolute;top:1000px;font-size: 1px;">s</p>
            <Notification></Notification>

        </div>
    </div>
</template>

<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"ImportDetails",
    components:{
        Notification,
        ParticleVue32
    },
    setup() {
      document.title = "Meta Shop - Import Details";
    },
    data(){
        return {
            url_img:'',
            pageN:1,
            import_details:null,
            sum_price:null,
            sum:null,
            importer:{
                id: null,
                importer_name: "",
                provider_id: null,
                provider_name: "",
                provider_tax_id: "",
                import_date: "",
                created_at: "",
                updated_at: ""
            },
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
            domain:'',
            idImport:'',
        }
    },
    mounted(){
        this.domain = config.API_URL;
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        // console.log(location.pathname.substring(24)); // '/admin/warehouse-import/77' -> cắt từ vị trí 24 sẽ được 77
        this.idImport = location.pathname.substring(24);
        BaseRequest.get('api/importdetails/'+this.idImport)
        .then( (data) =>{
            console.log(data);
            this.import_details = data.import_detail ;            
            this.sum_price = data.sum_price ;            
            this.sum = data.sum ;            
            this.importer = data.import ;            
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','View Detail Import Success !');
        }) 
        .catch(error=>{
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.message);
        })
        document.title = "Meta Shop | Import Details - "+this.idImport;
    },

    methods:{
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spwarehouseimport:function(){
            this.$router.push({name:'WarehouseImport'});
        },
        spdetailsImport:function(){
            // this.$router.push({name:'ProductDetails'});
            window.location=window.location.href;
        },
        profile:function(){
            // this.$router.push({name:'ProfileAdmin'});
            window.print();
        },
    },
    watch:{

    }
}
</script>

<style scoped>
.bgcl {
    background-color: rgb(240, 240, 240);
}
#logo {
    position: absolute;
    /* top:190px; */
    top:0px;
    left:360px;
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
    margin-top: -90px;
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

.col-form-label{
    color: black;
    font-weight: bold;
}
#administrator{
    background-color: #F2F4F6;
    padding: 16px 30px;
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
/* #home:hover {
    text-decoration: underline;
} */
#spwarehouseimport{
    color: #0085FF;
    cursor: pointer;
}
#spdetailsImport{
    color: #3a9efb;
    cursor: pointer;
}
/* #spwarehouseimport:hover {
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
    padding: 0px 30px;
    margin: 10px 30px;
    background-image: linear-gradient(120deg, white 0%, #ffffff 100%);
    /* box-shadow: 0px 10px 10px -10px gray; */
    /* box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset; */
    box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
    border-radius: 10px;
    align-items: center;
    width: 93%;
    font-size: 13px;
    /* border: 10px solid red; */
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
    padding: 30px 0px 10px 0px;
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



/* Table */
.btn {
    transition: all 1s ease;
}

/* @media print{
    body{
        padding:0px;
    }
    #head { display: none; }
    #big {
        background: #F2F4F6;
    }
    #printMe {
        display: block;
    }
    .dt1 {
        display: none;
    }
    .noprint{
        display: none;
    }
    #toptable{
        display: none;
    }
} */

</style>
