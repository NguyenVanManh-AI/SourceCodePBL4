<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spproductadmin" id="spproductadmin">Product</span></div>
                    <div style="font-weight: bold">Product</div>
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
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class='bx bxl-dropbox'></i> Product Table</div>
                    <!-- search  -->
                    <div id="search2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span @click="clicksearch" class="input-group-text" id="validationTooltipUsernamePrepend"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                            <input v-model="searchad" style="width:400px;border-top-right-radius: 6px;border-bottom-right-radius: 6px;" type="text" class="form-control" id="validationTooltipUsername" placeholder="Search Information Product" aria-describedby="validationTooltipUsernamePrepend" required>
                            <div class="invalid-tooltip">
                                Please choose a unique and valid username.
                            </div>
                        </div>
                    </div>              
                    <!-- search  -->
                    <div id="add_button"><button type="submit" class="mt-4 btn-pers" @click="addProduct"><i class="fa-solid fa-plus"></i> </button></div>
                </div>

                <!-- Sort by -->
                <div id="sortby">
                    <span style="color: black;font-size: 1rem;padding-top: 10px;padding-right: 10px;"><i class="fa-solid fa-filter"></i> Sorted By  </span>
                    <button type="button" :class="{clsort:sortlatest,cldf:!sortlatest}" id="esortlatest" @click="fsortlatest()"><i :class="{'fa-solid':true,'fa-arrow-up-short-wide':!sortlatest,'fa-arrow-down-short-wide':sortlatest}"></i> Latest</button>
                    <button type="button" :class="{clsort:sortname,cldf:!sortname}" id="esortname" @click="fsortname()"><i :class="{'fa-solid':true, 'fa-arrow-down-a-z':!sortname,'fa-arrow-down-z-a':sortname}"></i> Name</button>
                    <button type="button" :class="{clsort:unclassified,cldf:!unclassified}" id="eunclassified" @click="funclassified()"><i :class="{'fa-solid':true, 'fa-cubes':!unclassified,'fa-cube':unclassified}"></i> Unclassified</button>
                </div>
                <!-- Sort by -->

                <div id="bodytable">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col" style="text-align: center;">Product Images</th>
                            <th scope="col">Category Name</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody v-for="(pr,index) in products" :key="index">
                            <tr>
                                <th scope="row">{{(pageN-1)*10+index+1}}</th>
                                <td>{{pr.product_name}}</td>
                                <td>
                                    <div :id="'carouselExampleControls'+index" class="carousel slide" data-ride="carousel" v-if="images[index][0]">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active imgproduct">  <!-- nhằm đảm bảo luôn có ít nhất 1 ảnh để có slider nên có active cho cái đầu tiên -->
                                                <img class="d-block w-100" :src="domain+'/'+images[index][0].image_path" >
                                            </div>
                                            <div class="carousel-item imgproduct" v-for="(img,index2) in images[index]" :key="index2">
                                                <img class="d-block w-100" :src="domain+'/'+img.image_path" >
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" :href="'#carouselExampleControls'+index" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" :href="'#carouselExampleControls'+index" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </td>
                                <td v-if="!pr.category_name">Chưa Phân Loại</td>
                                <td v-if="pr.category_name">{{pr.category_name}}</td>
                                <td style=""><button type="button" class="btn btn-outline-primary" @click="viewDetail(pr.uri)" ><i class="fa-solid fa-bars-staggered"></i> View Detail</button></td>
                                <td style=""><button type="button" class="btn btn-outline-danger" @click="openModelDelete(pr.product_id)" data-toggle="modal" data-target="#exampleModalDelete"><i class="fa-solid fa-trash"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <br><br><br><br>

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
        <!-- Model Delete Provider -->
        <div class="modal fade" id="exampleModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Are you sure you want to delete this product ? <br>
                            This product will be permanently deleted from the system !
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="colsedele">Close</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteProduct">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Model Delete Provider -->
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
    name:"ProductAdmin",
    components:{
        Notification,
        paginate: Paginate,
        ParticleVue32
    },
    setup() {
        document.title = "Meta Shop - Product"
    },
    data(){
        return {
            url_img:'',
            quantity:null,
            searchad:'',
            pageN:1,
            products:null,
            images:null,
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
            sortlatest:false,
            sortname:false,
            unclassified:false,
            idDelete:null,
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
            // alert(this.pageN);
        }
        else {
            this.pageN = 1;
        }

        // Nếu như có biến search 
        if(urlParams.has('search')) {
            this.searchad = urlParams.get('search');
            this.pageN = 1;
        }
        
        // tương tự như khi gửi từ client lên server -> boolean về string hết 
        // khi ta lấy từ url xuống cũng vậy . về string hết nên để gán được boolean ta làm như dưới 
        // this.sortlatest = (urlParams.get('sortlatest') === 'true'); this.sortlatest = true nếu biến trên url là 'true'
        // this.sortlatest = false nếu biến trên url là 'fasle' . Nó không bằng chính giá trị 'true' , 'false'
        // mà nó là kết quả của việc đem cái trên url đi so sánh với 'true' nếu 'true' = 'true' => true 
        // tương tự với false 

        // Nếu như có biến sortlatest, sortname  
        if(urlParams.has('sortlatest')) this.sortlatest = (urlParams.get('sortlatest') === 'true');
        if(urlParams.has('sortname')) this.sortname = (urlParams.get('sortname') === 'true');
        if(urlParams.has('unclassified')) this.unclassified = (urlParams.get('unclassified') === 'true');

        BaseRequest.post('api/products?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&unclassified='+this.unclassified)
        .then( (data) =>{
            // console.log(data);
            this.quantity = data.quantity;
            this.products = data.product.data ;
            this.images = data.img;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get All Products Success !');

            // setTimeout(()=>{
            //     window.location=window.location.href;
            // }, 1500);
        }) 
        .catch(error=>{
            // console.log(error);
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.response.data.message);
        })
    },

    methods:{
        viewDetail:function(uri){
            this.$router.push({name:'ProductDetails',params:{id:uri}});
        },
        addProduct:function(){
            this.$router.push({name:'ProductAdd'});
        },
        // sort 
        fsortlatest:function(){
            this.sortlatest = !this.sortlatest;
            this.getdatasort();
        },
        fsortname:function(){
            this.sortname = !this.sortname;
            this.getdatasort();
        },
        funclassified:function(){
            this.unclassified = !this.unclassified;
            this.getdatasort();
        },
        getdatasort:function(){
            if(this.pageN == null) this.pageN=1;
            BaseRequest.post('api/products?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&unclassified='+this.unclassified)
            .then( (data) =>{
                this.quantity = data.quantity;
                this.products = data.product.data ;
                this.images = data.img;
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
        spproductadmin:function(){
            this.$router.push({name:'ProductAdmin'});
            this.searchad='';
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        openModelDelete:function(id){
            this.idDelete = id;
        },
        deleteProduct:function(){
            // alert(this.idDelete);
            BaseRequest.delete('api/products/'+this.idDelete)
            .then((data)=>{
                // console.log(data);
                var colsedele = window.document.getElementById('colsedele');
                colsedele.click();

                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                setTimeout(()=>{window.location=window.location.href;}, 2000);
            })
            .catch(()=>{
                // console.log(error);
                var colsedele = window.document.getElementById('colsedele');
                colsedele.click();

                const { emitEvent } = useEventBus();
                emitEvent('eventError','Delete Product False !');
                setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
        clickCallback:function(pageNum){
            BaseRequest.post('api/products?page='+pageNum+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&unclassified='+this.unclassified)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                this.pageN = pageNum;
                this.products = data.product.data ;
                this.images = data.img;

                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Get All Category Success !');

                // setTimeout(()=>{
                //     window.location=window.location.href;
                // }, 1500);
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
            window.location = window.location.pathname+"?search="+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&unclassified='+this.unclassified;
        }
    },
    watch:{
        searchad:function(){
            // console.log(this.searchad);
            this.pageN = 1 ;
            BaseRequest.post('api/products?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname+'&unclassified='+this.unclassified)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                // let urlParams = new URLSearchParams(window.location.search);
                // urlParams.set('page', this.pageN);
                // window.location.search.set('page',this.pageN);
                this.products = data.product.data ;
                this.images = data.img;
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
    box-shadow: 0px 10px 10px -10px #3b9cf8;
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
#spproductadmin{
    color: #3a9efb;
    cursor: pointer;
}
/* #spproductadmin:hover {
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

#divpaginate{
    display: flex;
    width: 100%;
    justify-content: center;
    position:absolute; /* giúp cho bao nhiêu dòng nó cũng không bị nhảy lên hay nhảy xuống */
    bottom: 20px; /* nếu ít dòng thì nó nhảy lên , nếu nhiều dòng thì nó thụt xuống nên mình cho nó cố định luôn */
    /* margin: 10px 30px; */
    padding-right: 50px;
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
#esortlatest ,#esortname, #eunclassified{
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
#esortname:hover {
    background-color: #0367c5;
    color: white;
}
#eunclassified:hover {
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


/* Img product */

.imgproduct {
    width: 280px;
    height: 100px;
}
.imgproduct img {
    width: 100%; /* or any custom size */
    height: 100%; 
    object-fit: contain;
}

/* .carousel-control-prev{
    margin-left: 10px;
}
*/
.carousel-control-next {
    margin-right: 10px;
} 
.carousel-control-prev-icon{
    background-color: #0085FF;
    border-radius: 6px;
    border: 3px solid #0085FF;
}
.carousel-control-next-icon{
    background-color: #0085FF;
    border-radius: 6px;
    border: 3px solid #0085FF;
}

/* Img product */

</style>
