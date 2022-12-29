<template>
    <div id="main">
        <div id="search_sort">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                </div>
                <input v-model="searchad" type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search by order code, recipient name or delivery address">
            </div>
            <div id="sortby">
                <span style="color: black;font-size: 1rem;padding-top: 10px;padding-right: 10px;"><i class="fa-solid fa-filter"></i> Sorted By  </span>
                <button type="button" :class="{clsort:sortlatest,cldf:!sortlatest}" id="esortlatest" @click="fsortlatest()"><i :class="{'fa-solid':true,'fa-arrow-up-short-wide':!sortlatest,'fa-arrow-down-short-wide':sortlatest}"></i> Latest</button>
            </div>
        </div>

        <div id="table_order">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Order code</th>
                        <th scope="col">Recipient name</th>
                        <th scope="col">Delivery address</th>
                        <th scope="col">Order date</th>
                        <th scope="col">Total</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(customer_order, index) in customer_orders" :key=index>
                        <th scope="row">{{(pageN-1)*5+index+1}}</th>
                        <td style="color:green">{{customer_order.hex_id}}</td>
                        <td>{{customer_order.recipient_name}}</td>
                        <td>{{customer_order.address}}</td>
                        <td>{{customer_order.order_time}}</td>
                        <td style="color:red">${{new Intl.NumberFormat().format(customer_order.total)}}</td>
                        <td><button type="button" class="btn btn-outline-primary" @click="viewDetail(customer_order.hex_id)">Details</button></td>
                        <td><button @click="{id_cancel = customer_order.id;}" data-toggle="modal" data-target="#cancelModal" type="button" class="btn btn-outline-danger">Cancel</button></td>
                    </tr> 
                </tbody>
            </table>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-triangle-exclamation"></i> Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        The order will be permanently deleted from the system ! <br>
                        Are you sure you want to cancel this order ?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button @click="cancelOrder" type="button" class="btn btn-danger"><i class="fa-solid fa-rectangle-xmark"></i> Cancel order</button>
                </div>
                </div>
            </div>
        </div>

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

        <Notification></Notification>
    </div>
</template>
<script>

import Notification from './../Notification'
import useEventBus from '../../../composables/useEventBus'
import BaseRequest from '../../../restful/user/core/BaseRequest';
import Paginate from 'vuejs-paginate-next';

export default {
    name: "WaitForConfirmation",
    components: {
        Notification,
        paginate: Paginate,
    },
    data(){
        return{
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
            customer_orders:null,
            sortlatest:false,
            searchad:'',
            pageN:1,
            quantity:null,
            id_cancel:null,
        }
    },
    setup() {
        document.title = "Meta Shop - Waiting for Confirmation";
    },
    mounted(){

        this.user = JSON.parse(window.localStorage.getItem('user'));

        const { emitEvent } = useEventBus();
        emitEvent('eventWaitForConfirmation');

        BaseRequest.get('api/customer-order/wait-confirm?id='+this.user.id)
        .then( (data) =>{
            this.customer_orders = data.customer_order.data;
            this.quantity = data.customer_order.total;
            emitEvent('eventUserSuccess','Show Orders Success !');
        }) 
        .catch(()=>{
          emitEvent('eventUserError','Show Orders Failse !');
        })
    },
    methods:{
        viewDetail:function(uri){
            this.$router.push({name:'OrderDetails',params:{id:uri}});
        },
        fsortlatest:function(){
            this.sortlatest = !this.sortlatest;
            const { emitEvent } = useEventBus();
            BaseRequest.get('api/customer-order/wait-confirm?id='+this.user.id+'&page='+this.pageN+'&search='+this.searchad+'&sort='+this.sortlatest)
            .then( (data) =>{
                this.customer_orders = data.customer_order.data;
                this.quantity = data.customer_order.total;
                emitEvent('eventUserSuccess','Show Orders Success !');
            }) 
            .catch(()=>{
                emitEvent('eventUserError','Show Orders Failse !');
            })
        },
        clickCallback:function(pageNum){
            this.pageN = pageNum;
            const { emitEvent } = useEventBus();
            BaseRequest.get('api/customer-order/wait-confirm?id='+this.user.id+'&page='+pageNum+'&search='+this.searchad+'&sort='+this.sortlatest)
            .then( (data) =>{
                this.customer_orders = data.customer_order.data;
                this.quantity = data.customer_order.total;
                emitEvent('eventUserSuccess','Show Orders Success !');
            }) 
            .catch(()=>{
                emitEvent('eventUserError','Show Orders Failse !');
            })
        },
        cancelOrder:function(){
            const { emitEvent } = useEventBus();
            BaseRequest.get('api/customer-order/cancel?id_cancel='+this.id_cancel+'&id_customer='+this.user.id)
            .then( () =>{
                emitEvent('eventUserSuccess','Cancel Success !');
            }) 
            .catch(()=>{
                emitEvent('eventUserError','Cancel failse !');
            })

            setTimeout(()=>{
                window.location = window.location.href;
            }, 1000);
        }
    },
    watch:{
        searchad:function(){
            const { emitEvent } = useEventBus();
            this.pageN = 1; 
            BaseRequest.get('api/customer-order/wait-confirm?id='+this.user.id+'&page='+this.pageN+'&search='+this.searchad+'&sort='+this.sortlatest)
            .then( (data) =>{
                this.customer_orders = data.customer_order.data;
                this.quantity = data.customer_order.total;
                emitEvent('eventUserSuccess','Show Orders Success !');
            }) 
            .catch(()=>{
                emitEvent('eventUserError','Show Orders Failse !');
            })
        }
    }
}
</script>
<style scoped>
#main {
    padding: 20px;
}

#sortby{
    margin-top: 10px;
    margin-bottom: 10px;
    width:100%;
    background-color: #F2F4F6;
    padding: 10px 0px 10px 10px;
}
#esortlatest {
    transition: all 0.5s ease;
    margin-right: 20px;
    outline: none;
    width:auto;
    height: 40px;
    padding: 0px 10px;
    font-size: 18px;
    border-radius: 8px;
    border: 1px solid #0085FF;
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

#table_order td{
    font-size: 16px;
}

#divpaginate {
    width: 100%;
    display: flex;
    justify-content: center;
}

</style>