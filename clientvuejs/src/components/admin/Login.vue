<template>
  <div id="main">
    <div id="header">
      <div id="img"><a @click="logoClick"><img src="../../assets/logo.png"></a></div>
      <div id="logo"><a @click="logoClick">Meta Shop</a></div>
      <div id="span"></div>
      <div id="title" @click="adminlogin"><a>Admin Login</a></div>
    </div>
      
    <div id="big">
      <div class="container">
          <form @submit.prevent="login()" action="http://127.0.0.1:8000/api/auth/login" autocomplete="on">
            <h4>Login</h4><br>
            <div class="input-form"><input type="email" v-model="loginAdmin.email" required><div class="underline"></div><label>Email</label></div><br>
            <div class="input-form"><input type="password" required v-model="loginAdmin.password"><div class="underline"></div><label>Password</label></div><br>
            <!-- <div class="alert alert-danger" v-if="error">{{error.response.data.error}}</div> -->
            <a class="text-primary" href="#" data-toggle="modal" data-target="#exampleModalForgotPassword">Forgot your password ? </a><br>

            <!-- Model Forgot Password -->
            <div class="modal fade" id="exampleModalForgotPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel"><i class="fa-brands fa-keycdn"></i> Add Account Admin</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
</template>

<script>
import BaseRequest from '../../restful/admin/core/BaseRequest';
import LoginRequest from '../../restful/admin/requests/LoginRequest'
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'

export default {
    name: "LoginAdmin",
    components: {
      Notification
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
        window.document.title='MetaShop | Login';
        if(window.localStorage.getItem('admin')){
            this.$router.push({name:"DashboardAdmin"});
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap');

 *{
   margin: 0;
   padding: 0;
   outline: none;
   box-sizing: border-box;
   font-family: 'Roboto', sans-serif;
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






 /* header */
 #header {
    display: flex;
    padding: 6px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    margin-bottom: 60px;
    align-items: center;
    border-radius: 16px;
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
}
#title {
    font-size: 20px;
    color: gray;
    margin-left: 20px;
    cursor: pointer;
}
#title:hover{
  text-decoration-color: #0085FF;
  text-decoration: underline;
  color: #0085FF;
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


</style>

