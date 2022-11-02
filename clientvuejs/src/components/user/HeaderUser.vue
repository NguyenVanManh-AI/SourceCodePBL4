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
            <input type="email" class="form-control" id="inputEmail3" placeholder="Search Information Product on Meta Shop">
          </div>
          <button type="button" @click="clicksearch" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
        <div id="cart">
          <div id="cart-shopping">
            <i class="fa-solid fa-cart-shopping"></i>
          </div>
          <div id="cart-number">{{this.count}}</div>
          <div id="list-cart">
            cddd
            <div style="width: 30px;height: 1000px;background-color: red;position: relative;">
            </div>
            sbc
          </div>
        </div>
        <div id="no-account" v-if="!user">
          <div id="no-ac-icon"><i class="fa-solid fa-user-plus"></i></div>
          <div id="add-ac">
            <button type="button" @click="login" class="btn btn-outline-primary" ><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</button>
            <button type="button" class="btn btn-outline-primary"><i class="fa-solid fa-user-plus"></i> Register</button>
          </div>
        </div>
        <div id="have-account" v-if="user">
          <div id="pr" @click="profile">
              <img :src="url_img" v-if="user.url_img!=null">
              <img src='../../assets/avatar.png' v-if="user.url_img==null">
              <p>{{user.fullname}}</p>
          </div>
          <div id="list-have-acc">

          </div>
        </div>
      </div>
      <div id="category">

      </div>
    </div>
</template>
<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
// import useEventBus from '../../composables/useEventBus';
// import Notification from './Notification';
import config from '../../config.js';

export default {
    name: "HeaderUser",
    data(){
      return{
        prevScrollpos : window.pageYOffset, // header

        categorys:null,
        // user: {
        //     id: 1,
        //     fullname: "Nguyễn Văn Mạnh pro vip vip",
        //     email: "buongtaynhenhang99@gmail.com",
        //     username: "vanmanh1pro",
        //     email_verified_at: null,
        //     address: "Hà Nội - Việt NamNam",
        //     date_of_birth: "2001-09-29",
        //     gender: 1,
        //     status: 1,
        //     phone: "0971404372",
        //     // url_img: "storage/images/SWw5qAdRDrziCfEcMHaqROFnRBxkoLuYWYL3M6HF.jpg",
        //     url_img: null,
        //     google_id: "110656888206613286806",
        //     remember_token: null,
        //     created_at: "2022-09-28T06:37:59.000000Z",
        //     updated_at: "2022-10-15T09:01:54.000000Z"
        // },
        user:null,
        count:123,
        url_img:''
      }
    },
    components: {

    },
    created () {
        window.addEventListener('scroll', this.handleScroll);  // header
    },
    unmounted () {
        window.removeEventListener('scroll', this.handleScroll); // header
    },
    mounted(){
      if(this.user != null && this.user.url_img != null) this.url_img = config.API_URL +'/'+ this.user.url_img;
      BaseRequest.get('api/categorys/allcategory')
        .then( (data) =>{
            this.categorys = data.category;
        }) 
        .catch(()=>{

        })
    },
    methods: {
      // header
      handleScroll () {
        var currentScrollPos = window.pageYOffset;
        if (this.prevScrollpos > currentScrollPos) {
            document.getElementById("header").style.top = "0";
        } else {
            document.getElementById("header").style.top = "-300px";
        }
        this.prevScrollpos = currentScrollPos;
      },
      // header

      login:function(){
        this.$router.push({name:"LoginUser"});
      },
      
    },
}
</script>

<style scoped>

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
  z-index: 1;
  transition: top 0.7s ease; /* để cho nó trượt xuống cho mượt */
}

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
#logo img {
  width: 50px;
  margin: auto;
  margin-bottom: 10px;
}
#logo p {
  font-weight: bold;
  color: #F84B2F;
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
  background-color: blue;
  overflow: auto;
  opacity: 0;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  transition: top 0.5s , opacity 1.5s ,width 0.1s, height 0.1s  ;
}

#cart:hover #list-cart{
  top:64px;
  opacity: 1;
  width: 380px;
  height: 400px;
  border: 1px solid #F84B2F;
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
  background-color: white;
  border-radius: 10px;
  opacity: 0;
  transition: top 0.5s , opacity 1.5s ,width 0.1s, height 0.1s  ;
}
#add-ac button {
  margin-top: 10px;
  margin-left: 20px;
}
#add-ac * {
  display: none;
}
#no-account:hover #add-ac{
  top:60px;
  opacity: 1;
  width: 200px;
  height: 110px;
  border: 1px solid #F84B2F;
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
  line-height: 100%;
  color: black;
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
  margin-right: 10px;
}
#pr p {
  padding: 10px 0px;
  font-weight: 700;
  width: 150px;
  overflow: hidden;
  line-height: 100%;
}

.network{
  transition: all 1s ease;
}
.network:hover{
  color: #f4fc97;
}

/* list-have-acc */
#list-have-acc{
  position: absolute;
  top: 150px;
  left:-16px;
  width: 0px;
  height: 0px;
  background-color: blue;
  opacity: 0;
  border-radius: 20px;
  transition: top 0.5s , opacity 1.5s ,width 0.1s, height 0.1s  ;
}

#have-account:hover #list-have-acc{
  top:50px;
  opacity: 1;
  width: 260px;
  height: 200px;
  border: 1px solid #F84B2F;
}

#category {
  height: 6px;
  background-color: #F84B2F;  
}



</style>