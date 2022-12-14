<template>
    <div id="header">
      <div id="top-line">
        <ul id="contact">
            <li>
              <a class="network" href="https://www.facebook.com/IcassNiuTon" target="_blank" style="margin-left: 10px;font-size: 23px;"><i class="fa-brands fa-facebook"></i></a>
              <a class="network" href="https://www.instagram.com/vanmanh_ai_machine_learning/" target="_blank" style="margin-left: 10px;font-size: 23px;"><i class="fa-brands fa-square-instagram"></i></a>
              <a class="network" href="https://www.linkedin.com/in/nvmanhfullstack/" target="_blank" style="margin-left: 10px;font-size: 23px;"><i class="fa-brands fa-linkedin"></i></a>
            </li>
            <li class="contactshop"><a href="mailto:meta.shop.sell@gmail.com" ><i class="fa-solid fa-envelope"></i> Email: meta.shop.sell@gmail.com</a></li>
            <li class="contactshop"><a href="tel:+84702518919" ><i class="fa-solid fa-phone"></i> Hotline 24/7 Phone: +84702518919</a></li>
            <li class="contactshop"><a @click="helpClick"><i class="fa-regular fa-circle-question"></i> Help </a></li>
        </ul>
      </div>
      <div id="main">
        <div id="logo" @click="logoClick">
          <img src="../../../dist/logo.png">
          <p>META SHOP</p>
        </div>
        <div id="search">
          <div class="col-sm-10">
            <input v-model="searchad" type="email" class="form-control" id="inputEmail3" placeholder="Search Information Product on Meta Shop">
          </div>
          <button type="button" @click="clicksearch" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
        <div id="cart" @click="clickUserOrder">
          <div id="cart-shopping">
            <i class="fa-solid fa-cart-shopping"></i>
          </div>
          <div id="cart-number" v-if="user_order != null">{{user_order.length}}</div>
          <div id="list-cart" v-if="user_order != null">
            <div id="inner_cart" >
              <li class="pr_cart" v-for="(pr,index) in user_order" :key="index">
                <div class="pr_cart_img">
                  <img :src="API_URL + '/' + pr.image_path">
                </div>
                <div class="pr_cart_name">
                  <p>Product Name : <span style="color:#0085FF">{{pr.product_name}}</span></p>
                  <p>
                    Price : <span>{{new Intl.NumberFormat().format(pr.price)}}$</span>
                    <span style="margin-left:30px"></span>
                    Quantity : <span>{{new Intl.NumberFormat().format(pr.buy_number)}}</span><br>
                    Total : <span>{{new Intl.NumberFormat().format(pr.price * pr.buy_number)}}$</span>
                  </p>
                </div>
              </li>
            </div>
          </div>
        </div>
        <div id="no-account" v-if="!user">
          <div id="no-ac-icon"><i class="fa-solid fa-user-plus"></i></div>
          <div id="add-ac">
            <ul id="add-ac-ul">
              <li @click="login" ><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</li>
              <li @click="register"><i class="fa-solid fa-user-plus"></i> Register</li>
            </ul>
          </div>
        </div>
        <div id="have-account" v-if="user">
          <div id="pr" @click="profile">
              <img :src="url_img" v-if="user.url_img!=null">
              <img src='../../assets/avatar.png' v-if="user.url_img==null">
              <p>{{user.fullname}}</p>
          </div>
          <div id="list-have-acc">
            <ul id="list-have-acc-ul">
              <li @click="myaccount"><i class="fa-solid fa-circle-user"></i> My Account </li>
              <li @click="order"><i class="fa-solid fa-bag-shopping"></i> Order </li>
              <li @click="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout </li>
            </ul>
          </div>
        </div>
      </div>
      <div id="category">
        <div class="sub-category" style="margin: 0px 30px 0px 30px;">
          <i class="fa-solid fa-shield"></i> Quality assurance
        </div>
        <div id="slide-category">
          <div id="carouselExampleControlsCategory" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div :class="{'carousel-item':true, 'active':index==0}" v-for="(categorys,index) in categoryss" :key="index">
                <div class="big-category">
                  <span class="sp-category" @click="clickCategory(category.name)" v-for="(category,index2) in categorys" :key="index2"><i class="fa-brands fa-shopify"></i> {{category.name.substr(0, 12)}}<span v-if="category.name.length>12" >...</span>,</span>
                </div>
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControlsCategory" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControlsCategory" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
        <div class="sub-category" style="margin: 0px 10px 0px 5px;width: 300px;">
          <i class="fa-solid fa-truck-fast"></i> <TypedText2></TypedText2>
        </div>
      </div>
    </div>
</template>
<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus';
// import Notification from './Notification';
import TypedText2 from "./../admin/typedtext/TypedText2.vue"
import config from '../../config.js';

export default {
    name: "HeaderUser",
    components: {
      TypedText2,
    },
    data(){
      return{
        prevScrollpos : window.pageYOffset, // header

        categoryss:null,
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
        count:123,
        url_img:'',
        API_URL : '',


      // search 
        searchad:'',
      // user order 
        user_order:null,
      }
    },
    created () {
        window.addEventListener('scroll', this.handleScroll);  // header
    },
    unmounted () {
        window.removeEventListener('scroll', this.handleScroll); // header
    },
    mounted(){

      this.API_URL = config.API_URL; 

      let urlParams = new URLSearchParams(window.location.search);
      if(urlParams.has('searchad')) {
        this.searchad = urlParams.get('searchad');
      }

      this.user = JSON.parse(window.localStorage.getItem('user'));

      if(this.user != null && this.user.url_img != null) this.url_img = config.API_URL +'/'+ this.user.url_img;
      BaseRequest.get('api/categorys/allcategory')
        .then( (data) =>{
            this.categoryss = data.category;
        }) 
        .catch(()=>{

        })

      // user order 
      this.user_order = JSON.parse(window.localStorage.getItem('user_order'));
      
      // event user order 
      const { onEvent } = useEventBus()
      onEvent('eventUserOrder',()=>{
        this.user_order = JSON.parse(window.localStorage.getItem('user_order'));
      });

    },
    methods: {
      // header
      handleScroll () {
        var currentScrollPos = window.pageYOffset;
        if (this.prevScrollpos > currentScrollPos) {
            document.getElementById("header").style.top = "0";
        } else {
            document.getElementById("header").style.top = "-400px";
        }
        this.prevScrollpos = currentScrollPos;
      },
      // header
      login:function(){
        this.$router.push({name:"LoginUser"});
      },
      register:function(){
        this.$router.push({name:"RegisterUser"});
      },
      logoClick:function(){
        this.$router.push({name:"DashboardUser"});
        // window.location = window.location.pathname;
        window.location = window.origin+'/main/dashboard';
      },

      logout:function(){
        window.localStorage.removeItem('user');
        this.$router.push({name:"LoginUser"});
        window.location = window.origin+'/main/login';
      },

      myaccount:function(){
        this.$router.push({name:"ProfileUser"});
      },
      order:function(){
        this.$router.push({name:"PurchaseOrderUser"});
      },

      clicksearch:function(){
        var url = new window.URL(document.location.href);
        url.searchParams.set("searchad",this.searchad);
        // window.location = url;
        window.location = window.origin+'/main/dashboard' + url.search;
      },
      clickCategory:function(name){
        var url = new window.URL(document.location.href);
        url.searchParams.set("category_name",name);
        // console.log(url);
        window.location = window.origin+'/main/dashboard' + url.search;
        // window.location = url;
      },

      clickUserOrder:function(){
        this.$router.push({name:"UserOrder"});
      }
    },
}
</script>

<style scoped>

#inner_cart {
  width: 100%;
  /* height: 1000px; */
  /* background-color: red; */
  position: relative;
}
.pr_cart {
  display: flex;
  height: 100px;
  background-color: white;
  align-content: center;
  align-items: center;
  margin-bottom: 10px;
  border-radius: 6px;
}
.pr_cart_img {
  margin-right: 10px;
  width: 100px;
  height: 100px;
}
.pr_cart_img img {
  width: 100%; 
  height: 100%; 
  object-fit: contain;
}
.pr_cart_name {
  width: calc(100% - 120px);
}
.pr_cart_name p:nth-child(1){
  line-height: 20px;
  font-size: 16px;
  color: black;
  font-weight: bold;
  margin-bottom: 6px;
}
.pr_cart_name p:nth-child(2) {
  color: black;
  font-weight: bold;
  line-height: 16px;
  font-size: 12px;
}
.pr_cart_name p:nth-child(2) span {
  color: red;
}


/* 
  <span class="sp-category" @click="clickCategory(category.name)" v-for="(category,index2) in categorys" :key="index2"><i class="fa-brands fa-shopify"></i> {{category.name.substr(0, 12)}}<span v-if="category.name.length>12" >...</span>,</span>
  => như thế này vuejs vẫn hiểu và vẫn truyền vào được từng cái , @click="clickCategory(category.name)
  không nhất thiết là phải có span v-for rồi trong một span nữa 
*/

/* RESET COLOR INPUT AND BUTTON */
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus {   
  border-color: #F84B2F;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px #F84B2F;
  outline: 0 none;
}
input,  input:active, input:visited {
  border-color: #F84B2F ;
  color: #F84B2F ;
  outline-color: #F84B2F;
}

input:hover{
  /* background-color: #F84B2F !important; */
  border-color: #F84B2F ;
}

/* thay đổi màu cho button */
.btn-outline-primary,  .btn-outline-primary:active, .btn-outline-primary:visited {
  border-color: #F84B2F ;
  color: #F84B2F ;
  outline-color: #F84B2F;
}
.btn{
  transition: all 0.6s ease;
}
.btn:focus, .btn:active {
  outline: none !important;
  box-shadow: none !important;
}
.btn-outline-primary:hover{
  background-color: #F84B2F !important;
  border-color: #F84B2F ;
}
/* RESET COLOR INPUT AND BUTTON */

#header {
  width: 100%;
  /* height: 100px; */
  /* border: 1px solid red; */
  /* cố định */
  position: -webkit-sticky;
  position: sticky;
  /* position: relative; */
  top: 0;
  background-color: white;
  /* cố định */
  z-index: 2; /* fix khỏi những cái khác */
  transition: top 0.7s ease; /* để cho nó trượt xuống cho mượt */
  border-bottom:2px solid white ;
}
/* z-index là một thuộc tính rất mạnh , nó có thể phân chia ra các lớp 
Ví dụ z-index : 2 sẽ nằm trên z-index : 1 
Ví dụ Thanh SideBar của user có một số style bị slide trong user đè lên 
=> để fix thì cho z-index : 1 , mặc khác ta cũng có head có z-index : 1 
mà sidebar nằm sau nên ưu tiên hơn header => kết qủa là sidebar đè lên header 

=> fix bằng cách cho header : z-index : 2 , sidebar : z-index : 1
    Để cho Header luôn là cái to nhất , nằm trên hết 

  + Ta có thể dùng z-index đánh số từ 1 đến n để phân lớp => một thuộc tính rất mạnh  

*/

/* contact */
#contact {
  width: 100%;
  height: 30px;
  display: flex;
  padding-right: 60px;
  justify-content: flex-end;
  background-color: #F84B2F;
  color: white;
  line-height: 30%;
  align-content: center;
  align-items: center;
}
#contact li {
  margin-left: 30px;
}
.contactshop a:hover {
  cursor: pointer;
  color: white;
}

/* main */
#main {
  width: 100%;
  height: 80px;
  display: flex;
  padding-left: 60px;
  background-color: white;
  line-height: 30%;
  align-content: center;
  align-items: center;
  /* border: 1px solid red; */
}
#logo {
  cursor: pointer;
}
#logo img {
  width: 50px;
  margin: auto;
  margin-bottom: 10px;
}
#logo p {
  font-weight: bold;
  color: #F84B2F;
  transition: color 0.5s ease;
}
#logo:hover p{
  color: #0085FF;
}





#search{
  margin-left: 60px;
  width: 60%;
  display: flex;
  justify-content: center;
  justify-items: center;
}

/* cart */
#cart {
  color: #F84B2F;
  position: relative;
  cursor: pointer;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 16px;
  padding-top: 16px;
  margin-left: -20px;
}
#cart #cart-shopping {
  font-size: 30px;
  padding-left:15px;
  padding-right: 15px;
  padding: 6px;
  border-radius: 6px;
  transition: all 0.5s ease;
}
#cart #cart-number{
  border: 1px solid #F84B2F;
  position: absolute;
  top:9px;
  left:45px;
  padding-left: 10px;
  padding-right: 10px;
  height: 20px;
  border-radius: 10px;
  background-color: white;
  display: flex;
  align-content: center;
  justify-content: center;
  line-height: 20px;
}

#list-cart{
  position: absolute;
  top: 150px;
  left:-160px;
  width: 0px;
  height: 0px;
  background-color: #F0F2F5;
  overflow: auto;
  opacity: 0;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  transition: top 0.5s , opacity 1.5s ,width 0.1s, height 0.1s  ;
  z-index: 1; /* vì trong header vẫn có slider các kiểu nên cũng phải để */
}

#cart:hover #list-cart{
  padding:10px 10px;
  padding-bottom: 0px;
  top:64px;
  opacity: 1;
  width: 380px;
  height: 400px;
  border: 2px solid #0085FF;
  display: block;
}
#cart:hover #cart-shopping {
  color: white;
  background-color: #F84B2F;
}

/* no-account */
#no-account {
  margin-left: 100px;
  position: relative;
  padding: 10px;
  transition: all 0.5s ease;
}
#no-ac-icon {
  font-size: 30px;
  color: #F84B2F;
  cursor: pointer;
  padding-left:15px;
  padding-right: 15px;
  padding: 6px;
  border-radius:6px ;
  transition: all 0.5s ease;
}
#no-ac-icon:hover {
  color:#ff2200;
}
#add-ac{
  position: absolute;
  top:140px;
  /* padding: 10px 20px 10px 20px; */
  left:-70px;
  width: 0px;
  height: 0px;
  background-color: #0085FF;
  border-radius: 10px;
  opacity: 0;
  transition: top 0.5s , opacity 1.5s ,width 0.1s, height 0.1s  ;
  z-index: 1; /* vì trong header vẫn có slider các kiểu nên cũng phải để */
}
#add-ac-ul {
  padding: 0px 0px;
  display: none;
}
#add-ac-ul li {
  cursor: pointer;
  padding:0px 0px;
  width: 100%;
  height: 40px;
  display: flex;
  align-content: center;
  align-items: center;
  color: white;
  font-weight: 600;
  transition: background-color 0.6s ease,color 0.6s ease;
  border-radius: 6px;
  margin-top: 6px;
}
#add-ac-ul li:hover {
  background-color: white;
  color: #0085FF;
}
#add-ac-ul li i {
  margin-right: 10px;
}
#no-account:hover #add-ac-ul li{
  padding:0px 10px;
}
#no-account:hover #add-ac-ul{
  padding: 10px 10px;
  display: block;
}

#no-account:hover #add-ac{
  top:60px;
  opacity: 1;
  width: 200px;
  height: 122px;
  border: 2px solid white;
}
#no-account:hover #no-ac-icon{
  color: white;
  background-color: #F84B2F;
}

#no-account:hover #add-ac button {
  display: block;
}
#no-account:hover #add-ac i {
  display: inline-block;
}

/* NOTE : 
  + Yêu cầu của loại này là không được để padding trong thẻ ẩn đi , ví dụ #add-ac 
  => vì bắt nguồn từ việc ta không display none (để đảm bảo giữ lại được hiệu ứng chuyển động)
  => không display none thực tế ta chỉ cho nó width và height là 0px , dù vậy nếu để padding 
  thì vẫn bị (vẫn tồn tại vùng padding đó) nên nếu ta lỡ hover vào vùng padding đó thì nó sẽ hiện ra 
  => để fix lỗi này thì ta cho những cái con của #add-ac (button ,...) margin để canh chỉnh 

  + Điều thứ 2 cần lưu ý là : giống như cái trên ta chỉ opacity 0 #add-ac , nên nếu cho bằng 1 thì 
  button vẫn hiện ra vì thế trước ban đầu ta cho 
    #add-ac * {
      display: none;
    } 
    => ẩn hết những gì có trong #add-ac

    // khi hover vào thì cho nó về kiểu ban đầu của nó 
    #no-account:hover #add-ac button {
      display: block;
    }
    #no-account:hover #add-ac i {
      display: inline-block;
    }

    chú ý là không : 
    #add-ac * {
      display: block;
    } 
    // vì tùy nữa , giống như trong đó có button và i mà cho nó block hết (khác ban đầu) => sẽ lỗi khung hình ngay . 
    => nên ban đầu nó là cái gì thì cho về cái đó . 

    Hoặc có cách khác nữa là chỉ khi hover vào thì mới cho #add-ac có padding bao nhiêu đó 
    sẽ đỡ phải đi style margin cho các button ,... 
    (áp dụng tương tự với button khi hover)

  + Nói thêm : 
    + Ngoài ra ta có thể thử với cách trong #add-ac chứa thêm một div to nhất , div này chứa tất cả 
    ban đầu cho nó none , sau đó cho nó block là cách tốt nhất để khỏi phải đi display từng cái 

*/

/* have-account */
#have-account {
  margin-left: 20px;
  position: relative;
}
#pr {
  height: 80px; /* cho chiều dài của nó to bằng thanh hiện tại là khỏe nhất */
  line-height: 100%;
  color: black;
  align-items: center;
  display: flex;
  cursor: pointer;
  margin-left: 20px;
  /* border: 1px solid red; */
}
#pr img{
  object-fit: cover;
  width: 40px;
  height: 40px;
  border-radius: 20px;
  margin-right: 10px;
}
#pr p {
  padding: 10px 0px;
  font-weight: 700;
  width: 150px;
  overflow: hidden;
  line-height: 100%;
  color:#0085FF;
  transition: all 0.5s ease;
  /* border: 1px solid red; */
}

.network{
  transition: all 1s ease;
}
.network:hover{
  color: #FFDE59;
}

/* list-have-acc */
#list-have-acc{
  position: absolute;
  top: 150px;
  left:-16px;
  width: 0px;
  height: 0px;
  background-color: #0085FF;
  opacity: 0;
  border-radius: 10px;
  transition: top 0.5s , opacity 1.5s ,width 0.1s, height 0.1s  ;
  z-index: 1; /* vì trong header vẫn có slider các kiểu nên cũng phải để */
}
#have-account:hover #list-have-acc{
  top:70px;
  opacity: 1;
  width: 260px;
  height: 160px;
  border: 2px solid white;
}
#have-account:hover #pr p{
  color:#F84B2F;
}



#category {
  background-color: #F84B2F;  
  padding: 3px 0px;
  display: flex;
}
#slide-category {
  color: white;
  width: 730px;
  height: 100%;
}
.big-category {
  display: flex;
  justify-content: space-between ;
  padding:0px 80px;
}
/* .sp-category{
  text-shadow: #FC0 1px 0 10px;
} */
.sp-category{
  font-weight: 600;
  cursor: pointer;
  transition: all 0.5s ease;
}
.sp-category:hover {
  text-decoration: underline;
  color: #FFDE59;
}
.sub-category {
  color: white;
  font-weight: 600;
}


/* list-have-acc-ul */
#list-have-acc-ul {
  padding: 0px 0px;
  display: none;
}
#list-have-acc-ul li {
  cursor: pointer;
  padding:0px 0px;
  width: 100%;
  height: 40px;
  display: flex;
  align-content: center;
  align-items: center;
  color: white;
  font-weight: 600;
  transition: background-color 0.6s ease,color 0.6s ease;
  border-radius: 6px;
  margin-top: 6px;
}
#list-have-acc-ul li:hover {
  background-color: white;
  color: #0085FF;
}
#list-have-acc-ul li i {
  margin-right: 10px;
}
#have-account:hover #list-have-acc-ul li{
  padding:0px 10px;
}
#have-account:hover #list-have-acc-ul{
  padding: 7px 10px;
  display: block;
}
</style>

<!--
  Trong mounted()       
    + Lấy các thông số quan trọng trước (những cái mà có khả năng lỗi thấp) 
    + Tránh để những cái dễ lỗi lên đầu vì nó sẽ không chạy tiếp chương trình . 
 -->