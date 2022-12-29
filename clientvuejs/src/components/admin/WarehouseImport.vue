<template>
    <div id="administrator">
      <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spwarehouseimport" id="spwarehouseimport">Warehouse Import</span></div>
                    <div style="font-weight: bold">Warehouse Import</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>
  
            <div id="table">
                <div id="toptable">
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-cart-flatbed"></i> Warehouse Import Table</div>
                    <div id="add_button"><button type="submit" class="mt-4 btn-pers" @click="addImport"><i class="fa-solid fa-plus"></i> </button></div>
                </div>

                <div id="sortby2">
                    <!-- search  -->
                    <div id="search2">
                        <div class="row">
                            <div class="col-5">
                                <div class="row"><label class="col col-form-label " >Search</label></div>
                                <div class="row"><div class="col"><input v-model="searchad" type="text" class="form-control" placeholder="Search Import"></div></div>
                            </div>
                            <div class="col-3">
                                <div class="row"><label class="col col-form-label"><i class="fa-solid fa-calendar-day"></i> From</label></div>
                                <div class="row"><div class="col"><input v-model="from_date" type="date" format="YYYY MM DD" class="form-control" placeholder="Price ($)"></div></div>
                            </div>
                            <div class="col-3">
                                <div class="row"><label class="col col-form-label"><i class="fa-solid fa-calendar-days"></i> To</label></div>
                                <div class="row"><div class="col"><input v-model="to_date" type="date" format="YYYY MM DD" class="form-control" placeholder="Tax (%) . 0-100% "></div></div>
                            </div>
                            <div class="col-1">
                                <div class="row" style="margin-top: 40px;"><div class="col"><button type="button" @click="clicksearch" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i></button></div></div>
                            </div>
                        </div>
                    </div>              
                    <!-- search  -->
                </div>
  
                <!-- Sort by -->
                <div id="sortby">
                    <span style="color: black;font-size: 1rem;padding-top: 10px;padding-right: 10px;"><i class="fa-solid fa-filter"></i> Sorted By  </span>
                    <button type="button" :class="{clsort:sortname,cldf:!sortname}" id="esortname" @click="fsortname()"><i :class="{'fa-solid':true, 'fa-arrow-down-a-z':!sortname,'fa-arrow-down-z-a':sortname}"></i> Name</button>
                    <button type="button" :class="{clsort:sortlatest,cldf:!sortlatest}" id="esortlatest" @click="fsortlatest()"><i :class="{'fa-solid':true,'fa-arrow-up-short-wide':!sortlatest,'fa-arrow-down-short-wide':sortlatest}"></i> Latest</button>
                </div>
                <!-- Sort by -->
  
                <div id="bodytable">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Import ID</th>
                            <th scope="col">Importer</th>
                            <th scope="col">Provider Name</th>
                            <th scope="col">Import Date</th>
                            <th scope="col">Total Money</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody v-for="(wareImport,index) in wareImports" :key="index">
                            <tr>
                                <th scope="row">{{(pageN-1)*10+index+1}}</th>
                                <td>{{wareImport.id}}</td>
                                <td>{{wareImport.importer_name}}</td>
                                <td>{{wareImport.provider_name}}</td>
                                <td>{{wareImport.import_date}}</td>
                                <td>$ {{new Intl.NumberFormat().format(sum_prices[index])}}</td>
                                <td style=""><button type="button" class="btn btn-outline-primary" @click="viewDetail(wareImport.id)" ><i class="fa-solid fa-bars-staggered"></i> View Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
  
                    <br><br><br><br>
  
                    <div id="divpaginate">
                        <paginate class="pag" id="nvm"
                            :page-count="Math.ceil(this.quantity/20)"
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
        <p style="position: absolute;top:1800px;font-size: 1px;">s</p>
  
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
    name:"WarehouseImport",
    components:{
        Notification,
        paginate: Paginate,
        ParticleVue32
    },
    setup() {
      document.title = "Meta Shop - Warehouse Import";
    },
    data(){
        return {
            url_img:'',
            quantity:null,
            searchad:'',
            pageN:1,
            wareImports:null,
            sum_prices:null,
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
            sortname:false,
            sortlatest:false,
            from_date:'',
            to_date:''
        }
    },
    mounted(){
        this.domain = config.API_URL;
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
        
        if(urlParams.has('sortname')) this.sortname = (urlParams.get('sortname') === 'true');
        if(urlParams.has('sortlatest')) this.sortlatest = (urlParams.get('sortlatest') === 'true');
        if(urlParams.has('from_date')) this.from_date = urlParams.get('from_date');
        if(urlParams.has('to_date')) this.to_date = urlParams.get('to_date');
  
        BaseRequest.post('api/importdetails/?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&from_date='+this.from_date+'&to_date='+this.to_date)
        .then( (data) =>{
            // console.log(data);
            this.quantity = data.quantity;
            this.wareImports = data.import.data ;
            this.sum_prices = data.sum_price ;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get All Import Success !');
        }) 
        .catch(error=>{
            // console.log(error);
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.response.data.message);
        })
    },
  
    methods:{
        viewDetail:function(id){
            this.$router.push({name:'ImportDetails',params:{id:id}});
        },
        addImport:function(){
            this.$router.push({name:'ImportProduct'});
        },
        // sort 
        fsortname:function(){
            this.sortname = !this.sortname;
            this.getdatasort();
        },
        fsortlatest:function(){
            this.sortlatest = !this.sortlatest;
            this.getdatasort();
        },
        getdatasort:function(){
            if(this.pageN == null) this.pageN=1;
            BaseRequest.post('api/importdetails/?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&from_date='+this.from_date+'&to_date='+this.to_date)
            .then( (data) =>{
              this.quantity = data.quantity;
              this.wareImports = data.import.data ;
              this.sum_prices = data.sum_price ;
            }) 
            .catch(error=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);
            })
        },
        // sort 
  
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spwarehouseimport:function(){
            this.$router.push({name:'WarehouseImport'});
            this.searchad='';
            // window.location=window.location.href;
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
        clickCallback:function(pageNum){
            BaseRequest.post('api/importdetails/?page='+pageNum+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&from_date='+this.from_date+'&to_date='+this.to_date)
            .then( (data) =>{
                this.quantity = data.quantity;
                this.wareImports = data.import.data ;
                this.sum_prices = data.sum_price ;
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Get All Import Success !');
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
            window.location = window.location.pathname+"?search="+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&from_date='+this.from_date+'&to_date='+this.to_date;
        }
    },
    watch:{
        searchad:function(){
            // console.log(this.searchad);
            this.pageN = 1 ;
            BaseRequest.post('api/importdetails/?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&from_date='+this.from_date+'&to_date='+this.to_date)
            .then( (data) =>{
              this.quantity = data.quantity;
              this.wareImports = data.import.data ;
              this.sum_prices = data.sum_price ;
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
  .detaiproduct:hover{
    color: #0085FF;
  }
  #administrator{
    background-color: #F2F4F6;
    padding: 16px 30px;
    position: relative;
  }
  
  #search {
    display: flex;
  }
  #search2{
    margin-right: 100px;
    margin-top: 4px;
    /* box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1); */
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
    background-color: white;
    /* box-shadow: 0px 10px 10px -10px gray; */
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    border-radius: 10px;
    align-items: center;
    width: 93%;
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
    top: -6px;
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


  
  #divpaginate{
    display: flex;
    width: 100%;
    justify-content: center;
    position:absolute; /* giúp cho bao nhiêu dòng nó cũng không bị nhảy lên hay nhảy xuống */
    bottom: 20px; /* nếu ít dòng thì nó nhảy lên , nếu nhiều dòng thì nó thụt xuống nên mình cho nó cố định luôn */
    /* margin: 10px 30px; */
    padding-right: 50px;
    font-size: 14px;
  }
  
  #validationTooltipUsernamePrepend{
    cursor: pointer;
  }
  #validationTooltipUsernamePrepend:hover{
    color: #0085FF;
  }
  
  #sortby{
    margin-top: 10px;
    margin-bottom: 30px;
    width:100%;
    background-color: #F2F4F6;
    padding: 10px 0px 10px 30px;
  }
  #sortby2{
    margin-top: 10px;
    width:100%;
    background-color: #F2F4F6;
    padding: 10px 0px 10px 30px;
  }
  #esortname ,#esortlatest, #eunclassified{
    margin-right: 20px;
    outline: none;
    width:auto;
    height: 40px;
    padding: 0px 10px;
    font-size: 14px;
    border-radius: 8px;
    border: 1px solid #0085FF;
  }
  #esortname:hover {
    background-color: #0367c5;
    color: white;
  }
  #esortlatest:hover {
    background-color: #0367c5;
    color: white;
  }
  .cldf{
    background-color:white;
    color: #0085FF;
  }
  .clsort{
    background-color: #0085FF;
    color: white;
  }
  /* Table */
  
  .table {
    font-size: 13px;
    background-image: linear-gradient(120deg, white 0%, #edf4fb 100%);
  }
  
  </style>
  