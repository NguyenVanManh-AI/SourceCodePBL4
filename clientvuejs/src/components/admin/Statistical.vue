<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spstatisticaladmin" id="spstatisticaladmin">Statistical</span></div>
                    <div style="font-weight: bold" class="ef">Statistical</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>

            <div id="table">
                <div id="statistical">
                    <div id="title1"><i class="fa-solid fa-chart-pie"></i> Statistics of sales and import data of metashop stores</div>
                    <div class="row" id="slsort">
                        <div id="title2"><i class="fa-solid fa-hockey-puck"></i> Statistics by</div>
                        <div class="col-3">
                            <select class="form-control" v-model="s_time">
                                <option value="year">This Year</option>
                                <option value="quarter">This Quarter</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                    </div>
                    <br><hr>
                    <div>
                        <div id="title3"><i class="fa-solid fa-chart-column"></i> Statistics of revenue, import goods</div>
                        <div id="infor_line">
                            <span><i class="fa-solid fa-file-invoice-dollar"></i> Revenue</span><span>$ {{new Intl.NumberFormat().format(total_datas_revenue)}}</span>
                            <span><i class="fa-solid fa-hand-holding-dollar"></i> Import goods</span><span>$ {{new Intl.NumberFormat().format(total_datas_import)}}</span>
                        </div>
                    </div>
                    <div class="chart_main">
                        <div id="chart_line">
                            <LineChart></LineChart>
                        </div>
                    </div>
                    <br><hr><br>
                    <div>
                        <div id="title4"><i class="fa-solid fa-basket-shopping"></i> Statistics of orders</div>
                        <div class="infor_donut">
                            <span id="sp1"><i class="fa-brands fa-shopify mr-1"></i> Wait for confirmation</span><p>{{new Intl.NumberFormat().format(data_donut[0])}}</p>
                            <span id="sp2"><i class="fa-regular fa-circle-check mr-1"></i> Waiting for delivery</span><p>{{new Intl.NumberFormat().format(data_donut[1])}}</p>
                        </div>
                        <div class="infor_donut">
                            <span id="sp3"><i class="fa-solid fa-truck-fast mr-1"></i> Delivering</span><p>{{new Intl.NumberFormat().format(data_donut[2])}}</p>
                            <span id="sp4"><i class="fa-solid fa-house-circle-check mr-1"></i> Delivered</span><p>{{new Intl.NumberFormat().format(data_donut[3])}}</p>
                            <span id="sp5"><i class="fa-solid fa-trash"></i> Cancelled</span><p>{{new Intl.NumberFormat().format(data_donut[4])}}</p>
                        </div>
                    </div>
                    <div class="chart_main">
                        <div id="chart_donut">
                            <DoughnutChart></DoughnutChart>
                        </div>
                    </div>
                    <br><hr><br>
                    <div>
                        <div id="title9"><i class="fa-solid fa-chart-line"></i> Detailed statistics of imported and sold products</div>
                        <div class="col-12">
                            <div class="col-auto">
                            <label class="sr-only" for="inlineFormInputGroup">Product information search</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                                    </div>
                                    <input type="text" v-model="searchad" class="form-control" id="inlineFormInputGroup" placeholder="Product information search">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Code</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Sold</th>
                                    <th scope="col">Import</th>
                                    <th scope="col">Revenue</th>
                                    <!-- <th scope="col">Profit</th> -->
                                    </tr>
                                </thead>
                                <tbody v-for="(pr,index) in products" :key="index">
                                    <tr>
                                        <th scope="row">{{(pageN-1)*10+index+1}}</th>
                                        <td @click="viewDetails(pr.uri)" class="view">{{pr.uri}}</td>
                                        <td @click="viewDetails(pr.uri)" class="view">{{pr.name}}</td>
                                        <td>{{new Intl.NumberFormat().format(pr.number_order)}}</td>
                                        <td>{{new Intl.NumberFormat().format(pr.number_import)}}</td>
                                        <td>${{new Intl.NumberFormat().format(pr.price_order)}}</td>
                                        <!-- <td>{{new Intl.NumberFormat().format(pr.price_order - pr.price_import)}}</td> -->
                                    </tr>
                                </tbody>
                            </table>

                            <div id="divpaginate">
                                <paginate class="pag" id="nvm"
                                    :page-count="Math.ceil(this.quantity/10)"
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

import LineChart from './admin_chart/LineChart.vue'
import DoughnutChart from './admin_chart/DoughnutChart.vue'
import Paginate from 'vuejs-paginate-next';

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"StatisticalAdmin",
    components:{
        Notification,
        ParticleVue32,
        LineChart,
        DoughnutChart,
        Paginate
    },
    setup() {
        document.title = "Meta Shop - Statistical"
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
            s_time:'year',
            total_datas_revenue : 0,
            total_datas_import : 0,
            data_donut : [],
            products : null,
            quantity : 0,
            pageN : 1,
            searchad:'',
        }
    },
    mounted(){
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        // line 
        var datas_revenue = [];
        var datas_import = [];
        var labels_line = [];

        // donut 
        var data_donut = []; 
        var labels_donut = ['Wait for confirmation','Waiting for delivery','Delivering','Delivered','Cancelled'];

        BaseRequest.get('api/statistical/chart?sort_by=year')
        .then( (data) =>{
            datas_revenue = data.datas_revenue;
            datas_import = data.datas_import;
            labels_line = data.labels_line;
            data_donut = data.data_donut;

            datas_revenue.forEach(a => {this.total_datas_revenue += a;});
            datas_import.forEach(a => {this.total_datas_import += a;});
            this.data_donut = data.data_donut;

            var ob_line = {
                datas_revenue : datas_revenue,
                datas_import : datas_import,
                labels_line : labels_line,
            }

            var ob_donut = {
                data_donut : data_donut,
                labels_donut : labels_donut,
            }

            const { emitEvent } = useEventBus();
            emitEvent('eventAdminStatistical',ob_line);
            emitEvent('event2AdminStatistical',ob_donut);

            emitEvent('eventSuccess','Statistical Success !');
        }) 
        .catch(()=>{
            const { emitEvent } = useEventBus();
            emitEvent('eventError','Statistical Failse !');
        })

        BaseRequest.get('api/statistical/product?searchad=')
        .then( (data) =>{
            this.products = data.products.data;
            this.quantity = data.products.total;
        }) 
        .catch(()=>{

        })
    },
    methods:{
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spstatisticaladmin:function(){
            this.$router.push({name:'StatisticalAdmin'});
            window.location = window.location.href;
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        viewDetails:function(uri){
            this.$router.push({name:'ProductDetails',params:{id:uri}});
        },
        clickCallback:function(pageNum){
            BaseRequest.get('api/statistical/product?searchad='+this.searchad+'&page='+pageNum)
            .then( (data) =>{
                this.products = data.products.data ;
                this.quantity = data.products.total;
                this.pageN = pageNum;

                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Get Products Success !');
            }) 
            .catch(()=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Get Products Fails !');
            })
        },
    },
    watch:{
        s_time:function(){
            // line 
            var datas_revenue = [];
            var datas_import = [];
            var labels_line = [];

            // donut 
            var data_donut = []; 
            var labels_donut = ['Wait for confirmation','Waiting for delivery','Delivering','Delivered','Cancelled'];

            BaseRequest.get('api/statistical/chart?sort_by='+this.s_time)
            .then( (data) =>{
                datas_revenue = data.datas_revenue;
                datas_import = data.datas_import;
                labels_line = data.labels_line;
                data_donut = data.data_donut;

                datas_revenue.forEach(a => {this.total_datas_revenue += a;});
                datas_import.forEach(a => {this.total_datas_import += a;});
                this.data_donut = data.data_donut;
            
                var ob_line = {
                    datas_revenue : datas_revenue,
                    datas_import : datas_import,
                    labels_line : labels_line,
                }

                var ob_donut = {
                    data_donut : data_donut,
                    labels_donut : labels_donut,
                }

                const { emitEvent } = useEventBus();
                emitEvent('eventAdminStatistical',ob_line);
                emitEvent('event2AdminStatistical',ob_donut);

                emitEvent('eventSuccess','Statistical Success !');
            }) 
            .catch(()=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Statistical Failse !');
            })
        },
        searchad:function(){
            BaseRequest.get('api/statistical/product?searchad='+this.searchad+'&page=1')
            .then( (data) =>{
                this.products = data.products.data ;
                this.quantity = data.products.total;

                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Search Products Success !');
            }) 
            .catch(()=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Search Products Fails !');
            })
        }
    }
}
</script>

<style scoped>

.view {
    cursor: pointer;
}

#divpaginate {
    width: 100%;
    display: flex;
    justify-content: center;
}
.view:hover {
    text-decoration: underline;
    color: #0085FF;
}

#title9 {
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
    font-size: 18px;
    font-weight: bold;
    color: rgb(210, 21, 87);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}
#title9 i {
    margin: 0px 6px;
}

*{
    list-style: none;
}

/* biểu đồ */
#statistical {
    padding: 30px 20px;
}
.chart_main {
    display: flex;
    justify-content: center;
}
#chart_line {
    width: 80%;
}
#chart_donut {
    width: 60%;
}


#title1 {
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
    font-size: 20px;
    font-weight: bold;
    color: rgb(31, 112, 31);
    text-transform: uppercase;
    letter-spacing: 1px;
}
#title1 i {
    margin-right: 6px;
}

#slsort{
    display: flex;
    align-items: center;
    align-content: center;
    margin-top: 10px;
    font-size: 16px;
    font-weight: bold;

}

#title3 {
    display: flex;
    align-items: center;
    align-content: center;
    margin-top: 10px;
    font-size: 20px;
    font-weight: bold;
    justify-content: center;
    margin-top: 10px;
    color: rgb(248, 178, 0);
}
#title3 i {
    margin: 0px 6px;
}
#infor_line {
    display: flex;
    align-items: center;
    align-content: center;
    margin-top: 10px;
    font-size: 14px;
    font-weight: bold;
    justify-content: center;
    margin-top: 6px;
}

#infor_line span:nth-child(2) {
    color: green;
    font-size: 20px;
    margin: 0px 10px;
}

#infor_line span:nth-child(4) {
    color: rgb(0, 190, 248);
    font-size: 20px;
    margin: 0px 10px;
}
#title4 {
    display: flex;
    align-items: center;
    align-content: center;
    margin-top: 10px;
    font-size: 20px;
    font-weight: bold;
    justify-content: center;
    margin-top: 10px;
    color: rgb(53, 144, 4);
}
#title4 i {
    margin: 0px 6px;
}
.infor_donut {
    display: flex;
    align-items: center;
    align-content: center;
    margin-top: 10px;
    font-size: 14px;
    font-weight: bold;
    justify-content: center;
    margin-top: 6px;
}
#sp1{
    color: rgb(255, 205, 86);
}
#sp2{
    color: rgb(54, 162, 235);
}
#sp3{
    color: #7ED957;
}
#sp4{
    color: #008037;
}
#sp5{
    color: rgb(255, 99, 132);
}

.infor_donut i {
    margin-left: 20px;
}

.infor_donut p {
    font-size: 18px;
    color: #0085FF;
    margin: 0px 6px;
}



/* header */
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

#spstatisticaladmin{
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
    /* min-height: 440px; */
    font-size: 13px;
    /* border: 10px solid red; */
}


</style>
