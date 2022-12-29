<template>
  <div id="main">

    <!-- HIỆU ỨNG BACKGROUND HÌNH TRÒN -->
    <div id="container-inside"> 
      <div id="circle-small"></div>
      <div id="circle-medium"></div>
      <div id="circle-large"></div>
      <div id="circle-xlarge"></div>
      <div id="circle-xxlarge"></div>
      <ParticleVue3/> <!-- ///+++ -->

    <!-- HIỆU ỨNG BACKGROUND HÌNH TRÒN -->
  
    <div id="header">
      <div id="img"><a @click="logoClick"><img src="../../assets/logo.png"></a></div>
      <div id="logo"><a @click="logoClick">Meta Shop</a></div>
      <div id="span"></div>
      <div id="title" @click="adminlogin" class="under"><a>Admin Login</a></div>
      <div id="typed"> <TypedText></TypedText></div>
    </div>
      
    <div id="big">
      <div class="container">
          <form  @submit.prevent="login()" action="http://127.0.0.1:8000/api/auth/login" >
            <h4 style="text-transform: uppercase;letter-spacing: 2.5px;font-weight: 700;color:#0085FF;"><i class="fa-solid fa-right-to-bracket"></i> LOGIN</h4><br>
            <div class="input-form"><input type="email" v-model="loginAdmin.email" required ><div class="underline"></div><label :class="{fix1:loginAdmin.email.length>0}"><i class="fa-solid fa-envelope"></i> Email</label></div><br>
            <div class="input-form" id="bigshow"><input type="password" required v-model="loginAdmin.password" ><div class="underline"></div><label><i class="fa-solid fa-lock"></i> Password</label>
              <!-- <div id="show"><input type="checkbox" v-model="showpw"></div> -->
            </div><br>
            <!-- <div class="alert alert-danger" v-if="error">{{error.response.data.error}}</div> -->
            <a class="text-primary under" style="text-decoration: none;" href="#" data-toggle="modal" data-target="#exampleModalForgotPassword" >Forgot your password ? </a><br>

            <!-- Model Forgot Password -->
            <div class="modal fade" id="exampleModalForgotPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel"><i class="fa-brands fa-keycdn"></i> Forgot your password !</h5>
                          <button type="button" style="margin-right: 10px;outline: none;margin-top: 3px;" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>
                      <div class="modal-body">
                          <form>
                              <div class="form-group">
                                  <label for="recipient-name" class="col-form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                                  <input v-model="resetPassword.email" type="email" class="form-control">
                              </div>
                          </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="closePW" >Close</button>
                          <button type="button" class="btn btn-outline-primary" @click="rspw">Submit</button>
                      </div>
                    </div>
                </div>
            </div>
            <!-- Model Forgot Password -->

            <button type="submit" class="mt-4 btn-pers" id="login_button" >Login</button>
          </form>
      </div>
    </div>
    <Notification></Notification>

  </div>
  </div>
</template>

<script>
import BaseRequest from '../../restful/admin/core/BaseRequest';
import LoginRequest from '../../restful/admin/requests/LoginRequest'
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'

import ParticleVue3 from "./particle/ParticleVue3.vue";
import TypedText from "./typedtext/TypedText.vue"

export default {
    name: "LoginAdmin",
    components: {
      Notification,
      ParticleVue3,
      TypedText
    },
    setup() {
      document.title = "Meta Shop - Login";
    },
    data(){
        return {
          loginAdmin:{
            email:'',
            password:''
          },
          resetPassword:{
            email:''
          },
          error:null,
          showpw:false,
          passwordType:'password',
      }
    },
    watch:{
      showpw:function(){
        if(this.showpw == true) this.passwordType = 'text';
        else this.passwordType = 'password'; 
      }
    },
    methods: {
      login:function(){
          // window.localStorage.setItem('admin',JSON.stringify(this.admin));
        // console.log(this.loginAdmin);
        var v = this.loginAdmin;
        LoginRequest.post('api/admin/login',this.loginAdmin)
        .then( data => {
          // console.log("login success !");
          // alert("Đăng nhập thành công !");
          this.setdata(data);
          this.error = null ;

          const { emitEvent } = useEventBus();
          emitEvent('eventSuccess','Login Success !');

          setTimeout(()=>{
            // console.log(data);
            this.$router.push({name:'DashboardAdmin'}); 
            window.location=window.location.href;
          }, 1000);
          
        })
        .catch( error => {
          this.loginAdmin = v; // để nó không reset ô input đi . 
          this.error = error;
          // console.log(error);

          const { emitEvent } = useEventBus();
          emitEvent('eventError',error.response.data.error);
          // console.log(error.response.data.error);
          // console.log("login false !");
        })
      },
      setdata:function(data){
        // console.log(data);
        var admin = {
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
          }
          admin = data.user;
          admin.access_token = data.message.original.access_token;
          window.localStorage.setItem('admin',JSON.stringify(admin));
      },
      logoClick:function(){
          this.$router.push({name:'UserComp'});
      },
      adminlogin:function(){
          window.location=window.location.href;
      },
      rspw:function(){
        BaseRequest.post('api/admin/reset-password',this.resetPassword)
        .then( () => { // chỉ cần email có trong hệ thống là nó không lỗi , còn thực tế tồn tại hay không không quan trọng 
            // console.log("login success !");
            // alert("Đăng nhập thành công !");
            this.error = null ;
            var closePW = window.document.getElementById('closePW');
            closePW.click();
            this.resetPassword.email = '';
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','We have e-mailed your password reset link !');

            setTimeout(()=>{
                // console.log(data);
                // this.$router.push({name:'DashboardAdmin'}); 
                // window.location=window.location.href;
            }, 1000);
        })
        .catch( error => {
            this.error = error;
            // console.log(error);

            const { emitEvent } = useEventBus();
            emitEvent('eventError','Reset password failed !');
            // console.log(error.response.data.error);
            // console.log("login false !");
        })
      }

    },
    mounted(){
      window.document.title='MetaShop - Login';
      if(window.localStorage.getItem('admin')){
          this.$router.push({name:"DashboardAdmin"});
      }
    }
}
</script>

<!-- Có một bug đó là 
  cái hiệu ứng ở input nó sẽ bị false khi mà google tự động điền 
  c1 : fix bằng cách khi mounted() thì auto focus vào nó -> nhưng mà vẫn còn khá xấu với lại nó chỉ focus được một cái 
  c2 : fix bằng cách cho click vào bất kì vị trí nào trên page cũng được (nhưng trong mounted thì lại cho .click() không được)
  c3 : dùng thư viện không cho nó tự động điền nữa (không làm mất chức năng lưu password của gg , click vào ô password thì nó cũng 
  gợi ý tự điền password cho ta thôi)
  thamkhao : https://stackoverflow.com/questions/61266151/vue-google-chrome-password-autofill-disable
    
    >npm i vue-disable-autocomplete
   
    import DisableAutocomplete from 'vue-disable-autocomplete';
    Vue.use(DisableAutocomplete);

    sau đó thêm : autocomplete="off" vào input 

  -->
<style scoped>

/* @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap'); */


/* HIỆU ỨNG BACKGROUND HÌNH TRÒN */
/* THAMKHAO : https://blog.stackfindover.com/css-background-animation-examples/ */
/*  */
#typed{
  width: 300px;
  height: 100%;
  font-size: 20px;
  margin-left: 40%;
  /* margin-left: 70px; */
  color: #0085FF;
  border-left-width: 6px ;
  border-left-style: solid;
  border-left-color: #0085FF;
  padding-left: 6px;
}
#main{
    background: #00adef;
    background: -moz-linear-gradient(-45deg, #00adef 0%, #0076e5 100%);
    background: -webkit-linear-gradient(-45deg, #00adef 0%,#0076e5 100%);
    background: linear-gradient(135deg, #00adef 0%,#0076e5 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00adef', endColorstr='#0076e5',GradientType=1 );
    position: relative;
    height: 700px;
    width: 100%;
    margin: 0px auto;
    padding: 0px auto;
    overflow: hidden; /* ẩn bớt phần dư của các hình trong đi */
}

#container-inside {
    position: relative;
    min-width: 960px;
    max-width: 1280px;
    height: auto;
    min-height: 100%;
    margin: 0px auto;
    padding: 0px auto;
    overflow: visible;
}

#circle-small {
  -webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation-timing-function: cubic-bezier(.6, 0, .4, 1);
  animation-delay: 0s;
  position: absolute;
  top: 200px;
  left: -150px;
  background: #fff;
  width: 300px;
  height: 300px;
  border-radius: 50%;
  opacity: 0.4;
}

#circle-medium {
  -webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation-timing-function: cubic-bezier(.6, 0, .4, 1);
  animation-delay: 0.3s;
  position: absolute;
  top: 50px;
  left: -300px;
  background: #fff;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  opacity: 0.3;
}

#circle-large {
  -webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation-timing-function: cubic-bezier(.6, 0, .4, 1);
  animation-delay: 0.6s;
  position: absolute;
  top: -100px;
  left: -450px;
  background: #fff;
  width: 900px;
  height: 900px;
  border-radius: 50%;
  opacity: 0.2;
}

#circle-xlarge {
  -webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation-timing-function: cubic-bezier(.6, 0, .4, 1);
  animation-delay: 0.9s;
  position: absolute;
  top: -250px;
  left: -600px;
  background: #fff;
  width: 1200px;
  height: 1200px;
  border-radius: 50%;
  opacity: 0.1;
}

#circle-xxlarge {
  -webkit-animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation: circle-small-scale 3s ease-in-out infinite alternate;
  animation-timing-function: cubic-bezier(.6, 0, .4, 1);
  animation-delay: 1.2s;
  position: absolute;
  top: -400px;
  left: -750px;
  background: #fff;
  width: 1500px;
  height: 1500px;
  border-radius: 50%;
  opacity: 0.05;
}

@-webkit-keyframes circle-small-scale {
    0% {
        -webkit-transform: scale(1.0);
    }
    100% {
        -webkit-transform: scale(1.1);
    }
}

@keyframes circle-small-scale {
    0% {
        transform: scale(1.0);
    }
    100% {
        transform: scale(1.1);
    }
}

/* HIỆU ỨNG BACKGROUND HÌNH TRÒN */


 *{
   margin: 0;
   padding: 0;
   outline: none;
   box-sizing: border-box;
   font-family: sans-serif;
 }
 #main{
  background-color: #F2F4F6;
  padding-top: 10px;
  padding-left: 30px;
  padding-right: 30px;
  height: 577px;
 }
 body{
   display: flex;
   align-items: center;
   justify-content: center;
   min-height: 100vh;
   background: linear-gradient(to right, #EF629F, #EECDA3);
 }

 /* input */
 .container{
  border-radius: 36px;
  width: 450px;
  background: #fff;
  padding: 30px;
  box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
 }
 .container .input-form{
   height: 40px;
   width: 100%;
   position: relative;
 }
 .container .input-form input{
   height: 100%;
   width: 100%;
   border: none;
   font-size: 17px;
   border-bottom: 2px solid silver;
 }
 .input-form input:focus ~ label,
 .input-form input:valid ~ label{
   transform: translateY(-20px);
   font-size: 15px;
   color: #0085FF;
 }
 .fix1{
  transform: translateY(-20px);
   font-size: 15px;
   color: #0085FF;
 }

 .container .input-form label{
   position: absolute;
   bottom: 10px;
   left: 0;
   color: grey;
   pointer-events: none;
   transition: all 0.3s ease;
 }
 .input-form .underline{
   position: absolute;
   height: 2px;
   width: 100%;
   bottom: 0;
 }
 .input-form .underline:before{
   position: absolute;
   content: "";
   height: 100%;
   width: 100%;
   background: #0085FF;
   transform: scaleX(0);
   transform-origin: center;
   transition: transform 0.3s ease;
 }
 .input-form input:focus ~ .underline:before,
 .input-form input:valid ~ .underline:before{
   transform: scaleX(1);
 }
/* input */

/* show password */
#bigshow{
  /* padding-right: 30px; */
  position: relative;
}
#show {
  position: absolute;
  right: 0px;
  top:11px;
  padding:6px;  
  display: flex;
  /* border-top-right-radius: 10px; */
  border-top-left-radius: 10px;
  align-items: center;
  border: 2px solid #0085FF;
}

 /* header */
 #header {
    /* opacity: 0.9; */
    display: flex;
    padding: 6px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    margin-bottom: 60px;
    align-items: center;
    border-radius: 16px;
    position: relative;  /* LÀM NHƯ THẾ NÀY ĐỂ KHÔNG BỊ HIỆU ỨNG background HÌNH TRÒN ĐÈ LÊN PHÍA TRÊN */
}
#header #img {
    cursor: pointer;
}
#header img {
    width: 80px;
}
#span {
    border: 1px solid gray;
    background-color: gray;
    height: 80px;
    margin-left: 20px;
}

@import url('https://fonts.googleapis.com/css2?family=Reem+Kufi+Ink');

#logo {
    font-size: 30px;
    font-family: 'Reem Kufi Ink', sans-serif;
    color: #0085FF;
    cursor: pointer;
}
#big {
    justify-content: center;
    display: flex;
    position: relative; /* LÀM NHƯ THẾ NÀY ĐỂ KHÔNG BỊ HIỆU ỨNG background HÌNH TRÒN ĐÈ LÊN PHÍA TRÊN */
}
#title {
    font-size: 20px;
    color: gray;
    margin-left: 20px;
    cursor: pointer;
}
#title:hover{
  /* text-decoration-color: #0085FF;
  text-decoration: underline;
  color: #0085FF; */
  color:#0085FF;
  transition: color 0.5s ease;
}




 /* btn Login */
 .btn-pers {
  position: relative;
  left: 50%;
  padding: 1em 2.5em;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  font-weight: 700;
  color: #000;
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
 /* btn Login */

 /* under */
.under{
    position: relative;
    padding: 0px 0px;
}
.under::after{
    content: ' ';
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 0;
    height: 2px;
    background:#0085FF;
    transition: width 0.3s;
}
.under:hover::after {
    width: 100%;
    transition: width 0.3s;
}
 /* under */


/*

:class="{fix1:loginAdmin.email.length>0}"
fix để khi đã có kí tự thì nó không còn nhảy email xuống đè lên chữ nữa . 
thanh password thì không bị nhưng email thì lại bị 

*/
</style>

