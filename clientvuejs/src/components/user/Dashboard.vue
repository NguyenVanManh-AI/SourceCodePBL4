<template>
    <div id="bigmain">
      <div id="main">
        <div id="left">
          <div id="category_sidebar" v-if="!status_show">
            <div id="top_category"><i class="fa-solid fa-list"></i> Category</div>
            <div id="list_category">
              <li v-for="(category,index) in categorys" :key="index" @click="categoryClick(category.name)" ><i class="fa-solid fa-angles-right"></i> {{category.name}}</li>
            </div>
          </div>  
          <div id="fill_sidebar" v-if="status_show">
            <div id="title_fill"><i class="fa-solid fa-filter-circle-dollar"></i> Search filter</div>
            <div id="from_to">
              <div class="row">
                <span>$ From</span>
                <div class="col-8">
                  <input type="number" min="0" v-model="price_from" class="form-control col-12" placeholder="$ From">
                </div>
              </div>
              <div class="row" >
                <span style="margin-right:20px">$ To</span>
                <div class="col-8" style="display:flex;align-item:center">
                  <input type="number" v-model="price_to" min="0" class="form-control col-12" placeholder="$ To">
                </div>
              </div>
            </div>
            <button class="btn btn-primary col-12 mx-auto" @click="applyPrice">APPLY</button>
          </div>
        </div>
        <div id="right_bar" >
          <div id="search" class="row">
            <div class="col-2"><i class="fa-solid fa-filter"></i> Sorted By</div>
            <div class="col-1.5">
              <button :class="{btn:true,'btn-outline-primary':sortlatest==false,'btn-primary':sortlatest==true}" @click="clickLatest"><i class="fa-solid fa-star"></i> Latest</button>
            </div>
            <div class="col-3">
              <select class="form-control" v-model="sortprice" @change="sortByPrice">
                <option value="false">$ Price</option>
                <option value="false">Low to High</option>
                <option value="true">High to Low</option>
              </select>
            </div>
            <div class="col-1.5">
              <button :class="{btn:true,'btn-outline-primary':status_show==false,'btn-primary':status_show==true}" @click="status_show = !status_show"><i class="fa-solid fa-filter-circle-dollar"></i> Filter Price</button>
            </div>
          </div>

          <div id="product">
            <li v-for="(pr,index) in products" :key="index" class="li_product">
              <div class="top">
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
                  <a class="carousel-control-next"  :href="'#carouselExampleControls'+index" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>
              <div>
                <p class="product_name" @click="viewDetail(pr.uri)"><i class="fa-brands fa-dropbox" style="margin-top:4px;margin-right:2px"></i> {{pr.product_name}}</p>
                <p class="product_price">$ {{new Intl.NumberFormat().format(pr.price)}}</p>
              </div>
              <span></span>
            </li>
          </div>
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



      <Notification></Notification>
    </div>
</template>

<script>

import BaseRequest from '../../restful/user/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'
import Paginate from 'vuejs-paginate-next';

export default {
  name: "DashboardUser",
  components: {
    Notification,
    paginate: Paginate,
  },
  data(){
    return {
      categorys:null,
      products:null,
      quantity:null,
      images:null,
      domain:'',
      // n:0,

      // search 
      searchad:'',
      category_name:'',
      price_from:0,
      price_to:0,
      sortlatest:false,
      sortprice:false,
      status_show:false,
      page:1,

    }
  },
  created(){

  },
  setup() {
    document.title = "Meta Shop - Dashboard";
  },
  mounted(){

    var txtquery = 'api/dashboard-customer/all-products3?qr=1';
      
    let urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('searchad')) {
      txtquery = txtquery + '&searchad='+urlParams.get('searchad'); 
      this.searchad = urlParams.get('searchad');
      this.status_show = true;
    }
    // không đặt search vì trùng với window.location.search
    if(urlParams.has('category_name')) {
      txtquery = txtquery + '&category_name='+urlParams.get('category_name');
      this.category_name = urlParams.get('category_name');
      // this.status_show = true;
    }

    if(urlParams.has('price_from')) {
      txtquery = txtquery + '&price_from='+urlParams.get('price_from');
      this.price_from = urlParams.get('price_from') ;
    }

    if(urlParams.has('price_to')) {
      txtquery = txtquery + '&price_to='+urlParams.get('price_to');
      this.price_to = urlParams.get('price_to') ;
    }

    if(urlParams.has('sortlatest')) {
      txtquery = txtquery + '&sortlatest='+urlParams.get('sortlatest');
      this.sortlatest = (urlParams.get('sortlatest') === 'true') ;
    }

    if(urlParams.has('sortprice')) {
      txtquery = txtquery + '&sortprice='+urlParams.get('sortprice');
      this.sortprice = (urlParams.get('sortprice') === 'true') ;
    } 

    if(urlParams.has('page')) {
      txtquery = txtquery + '&page='+urlParams.get('page');
    }
    this.domain = config.API_URL;
    // category 
    BaseRequest.get('api/dashboard-customer/get-category')
      .then( (data) =>{
          this.categorys = data.category ;
          const { emitEvent } = useEventBus();
          emitEvent('eventUserSuccess','Get All Category Success !');
      }) 
      .catch(()=>{
        const { emitEvent } = useEventBus();
        emitEvent('eventUserError','Get All Category Failse !');
      })

      // product 
      BaseRequest.get(txtquery)
      .then( (data) =>{
          this.products = data.product.data ;
          this.quantity = data.product.total;
          this.images = data.img;
          const { emitEvent } = useEventBus();
          emitEvent('eventUserSuccess','Get All Product Success !');
      }) 
      .catch(()=>{
        const { emitEvent } = useEventBus();
        emitEvent('eventUserError','Get All Product Failse !');
      })
  
  },
  methods: {
    clickCallback:function(pageNum){
      let urlParams = new URLSearchParams(window.location.search);
      // console.log('api/dashboard-customer/all-products3?page='+pageNum+"&"+urlParams);
      BaseRequest.get('api/dashboard-customer/all-products3?page='+pageNum+"&"+urlParams)
        .then( (data) =>{
          this.products = data.product.data ;
            this.quantity = data.product.total;
            this.images = data.img;
            const { emitEvent } = useEventBus();
            emitEvent('eventUserSuccess','Get All Product Success !');
        }) 
        .catch(()=>{
          const { emitEvent } = useEventBus();
          emitEvent('eventUserError','Get All Product Failse !');
        })
    },
    applyPrice:function(){
      var url = new window.URL(document.location.href);
      url.searchParams.set("price_from",this.price_from);
      url.searchParams.set("price_to",this.price_to);
      window.location = url;
    },
    // plus:function(){
    //   this.n++;
    //   var ob = {
    //     n:this.n,
    //     name:'vanmanh'
    //   }
    //   const { emitEvent } = useEventBus();
    //   emitEvent('eventPlus',ob);
    // }
    viewDetail:function(uri){
      this.$router.push({name:'ProductUserDetails',params:{id:uri}});
    },
    categoryClick:function(name){
      var url = new window.URL(document.location.href);
      url.searchParams.set("category_name",name);
      window.location = url;
    },
    clickLatest:function(){
      this.sortlatest = !this.sortlatest;
      var url = new window.URL(document.location.href);
      url.searchParams.set("sortlatest",this.sortlatest);
      window.location = url;
    },
    sortByPrice:function(){
      var url = new window.URL(document.location.href);
      url.searchParams.set("sortprice",this.sortprice);
      window.location = url;
    },

  }
}
</script>

<style scoped>


/* Thay thế tham sô trên url  
var url = new window.URL(document.location); // fx. http://host.com/endpoint?abc=123
      url.searchParams.set("foo", "bar");
      console.log(url.toString()); // http://host/endpoint?abc=123&foo=bar
      url.searchParams.set("foo", "ooft");
      console.log(url.toString()); // http://host/endpoint?abc=123&foo=ooft */

/* 
<option>&#xf005; Low to High</option> // icon trong option , f005 là mã code lấy trong 
icon font awesome (góc trên cùng bên phải) của mỗi icon khi click vào
 */

/* select {
  font-family: 'FontAwesome', 'sans-serif';
} */

#divpaginate{
    width: 200px;
    margin:20px auto;
}

#bigmain {
  background-color: #F0F2F5;
}
#main {
  display: flex;
}
#category_sidebar {
  width: 200px;
  margin: 6px;
  margin-top: 0px;
  padding: 10px;
}
#right_bar {
  margin-left: 10px;
  width: calc(100% - 233px);
}
#top_category {
  width: 200px;
  color: #0085FF;
  font-size: 18px;
  font-weight: bold;
  padding: 10px;
  background-color: white;
  margin-bottom: 10px;
}
#list_category {
  width: 200px;
  height: 500px;
  overflow: hidden;
  overflow-y: scroll;
  background-color: white;
  padding-left: 16px;
  padding-right: 16px;
}
#list_category li {
  height: 30px;
  padding-top:6px;
  padding-bottom:6px;
  padding-left: 6px;
  cursor: pointer;
  font-weight: bold;

  /* thêm 3 chấm đằng sau nếu như dài */
  text-overflow: ellipsis;
  white-space: nowrap; 
  overflow: hidden;
  /* thêm 3 chấm đằng sau nếu như dài */
}
#list_category li i {
  display: none;
}
#list_category li:hover {
  /* text-decoration: underline; */
  color: #0085FF;
  padding-left: 10px;
}
#list_category li:hover i{
  display: inline;
}

#fill_sidebar {
  width: 200px;
  margin-left: 16px;
  margin-top: 10px;
  margin-right: -4px;
  padding: 10px;
  background-color: white;
  height: 500px;
}
#search {
  padding: 10px;
  padding-left: 100px;
  margin-left: 10px;
  display: flex;
  align-content: center;
  align-items: center;
  background-color: white;
  height: 47px;
  margin-top: 10px;
  width: 100%;
  font-weight: bold;
  color: #0085FF;
}
#title_fill {
  font-weight: bold;
  color: #0085FF;
  font-size: 20px;
}
#from_to {
  /* display: flex;
  align-content: center;
  align-items: center;
  justify-content: space-between; */
  margin-top: 10px;
  padding-left: 20px;
  font-size: 14px;
}
#from_to input {
  font-size: 10px;
}
#from_to > div {
  display: flex;
  align-content: center;
  align-items: center;
  margin-bottom: 10px;
}
li {
  list-style: none;
}

#from_to button {
  font-weight: bold;
}




#product {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  /* background-color: white; */
  /* margin-left: 10px; */
}

.li_product{
  margin: 10px;
  background-color: white;
  border: 19px solid white;
}
.top {
  width: 100%;
}

/* Img product */

.imgproduct {
    width: 200px;
    height: 200px;
}
.imgproduct img {
    width: 100%; /* or any custom size */
    height: 100%; 
    object-fit: contain;
}
.carousel-control-prev:hover .carousel-control-prev-icon{
  background-color: #0085FF;
  border-radius: 30px;
  display: block;
  width: 40px;
  height: 60px;
}

.carousel-control-next:hover .carousel-control-next-icon{
  background-color: #0085FF;
  border-radius: 30px;
  display: block;
  width: 40px;
  height: 60px;
}

.li_product:hover .carousel-control-prev-icon{
  background-color: #0085FF;
  border-radius: 30px;
  display: block;
  width: 40px;
  height: 60px;
  transition: all 0.5s ease;
}

.li_product:hover .carousel-control-next-icon{
  background-color: #0085FF;
  border-radius: 30px;
  display: block;
  width: 40px;
  height: 60px;
  transition: all 0.5s ease;
}
.product_name {
  background-color: #0085FF;
  height: 30px;
  display: flex;
  justify-content: center;
  font-size:16px;
  padding-top: 5px;
  font-weight: bold;
  color: white;
  width: 200px;
  /* thêm 3 chấm đằng sau nếu như dài */
  text-overflow: ellipsis;
  white-space: nowrap; 
  overflow: hidden;
  /* thêm 3 chấm đằng sau nếu như dài */
  cursor: pointer;
}
.product_name:hover {
  text-decoration: underline;
}

.product_price{
  border: 4px solid #0085FF;
  color: red;
  font-size: 20px;
  display: flex;
  justify-content: center;
  align-content: center;
  font-weight: 600;
}

</style>