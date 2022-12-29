<template>
    <div id="register">
    <!-- HIỆU ỨNG BACKGROUND HÌNH TRÒN -->
    <div id="container-inside"> 
      <div id="circle-small"></div>
      <div id="circle-medium"></div>
      <div id="circle-large"></div>
      <div id="circle-xlarge"></div>
      <div id="circle-xxlarge"></div>
    <!-- HIỆU ỨNG BACKGROUND HÌNH TRÒN -->

        <ParticleVue3></ParticleVue3>
        <div id="main">
            <div id="top-main">Create Account</div>
            <div id="login-google">
                <div class="lb-gg">
                    <span></span> <label> Singin with</label> <span></span>
                </div>
                <div id="bt-gg" @click="loginGoogle">
                    <img src="../../assets/google_logo.png">
                    <span>Sing in with Google</span>
                </div>
                <div class="lb-gg">
                    <span></span> <label> Or Register Account</label> <span></span>
                </div>
            </div>

            <form @submit.prevent="register()" action="http://127.0.0.1:8000/api/customer/register" >
                <div class="form-group row">
                    <div class="col-12">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-user-check"></i> Full Name</label>
                        <input type="text" v-model="userRegister.fullname" class="form-control col-12" placeholder="Full Name">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-cake-candles"></i> Date Of Birth</label>
                        <input type="date" v-model="userRegister.date_of_birth" format="YYYY MM DD" class="form-control col-12" placeholder="Example input">
                    </div>
                    <div class="col-3">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-venus-mars"></i> Gender</label>
                        <select class="form-control" v-model="userRegister.gender">
                            <option value="1">Men</option>
                            <option value="0">Women</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-phone"></i> Phone</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend"><div class="input-group-text">+84</div></div>
                            <input type="text" v-model="userRegister.phone" class="form-control"  placeholder="Phone">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-location-dot"></i> Address</label>
                        <input type="text" v-model="userRegister.address" class="form-control"  placeholder="Address">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-5">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-user-check"></i> Username</label>
                        <input type="text" v-model="userRegister.username" class="form-control col-12"  placeholder="Username">
                    </div>
                    <div class="col-7">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-envelope"></i> Email</label>
                        <input type="email" v-model="userRegister.email" class="form-control col-12"  placeholder="Email">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-key"></i> Password</label>
                        <input type="password" v-model="userRegister.password" class="form-control col-12"  placeholder="Password">
                    </div>
                    <div class="col-6">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-check-double"></i> Confirm Password</label>
                        <input type="password" v-model="userRegister.password_confirmation" class="form-control col-12"  placeholder="Confirm Password">
                    </div>
                </div>

                <button type="submit" class="mt-4 btn-pers" id="login_button" >Register</button>
                <!-- Khi tạo form nên để email kế tiếp password để trình duyệt nhận dạng được 
                từ đó nếu có tự động điền hay check xem đúng định dạng chưa cũng dễ  -->
            </form>
            <div id="login-here">Have already an account ?<span @click="alreadyAcc">Login here.</span></div>

        </div>
        <Notification></Notification>
    </div>
    </div>
</template>


<script scoped>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus';
import Notification from './Notification';
import config from '../../config.js';

import ParticleVue3 from "./../admin/particle/ParticleVue3.vue";

export default {
    name : "RegisterUser",
    components: {
      Notification,
      ParticleVue3
    },
    created(){
        document.title = "Meta Shop - Register";
    },
    data(){
        return{
            userRegister:{
                fullname:'',
                username:'',
                email:'',
                password:'',
                password_confirmation:'',
                address:'',
                date_of_birth:'',
                phone:'',
                gender:'1',
            },
            err:{
                fullname:[],
                username:[],
                email:[],
                password:[],
                password_confirmation:[],
                address:[],
                date_of_birth:[],
                phone:[],
                gender:[],
            },
        }
    },
    setup(){
      return {

      }
    },
    computed(){

    },
    mounted(){
        if(window.localStorage.getItem('user')){
            this.$router.push({name:"DashboardUser"});
        }
    },
    methods:{
        loginGoogle:function(){
          window.location = config.API_URL + '/auth/google';
        },
        register:function(){
            var user = this.userRegister;
            BaseRequest.post('api/customer/register',this.userRegister)
            .then( () =>{

                const { emitEvent } = useEventBus();
                emitEvent('eventUserSuccess','You have successfully registered your account, now login !');
                setTimeout(()=>{
                    this.$router.push({name:'LoginUser'}); 
                }, 1500);

            }) 
            .catch(error=>{
                this.err = error.response.data;
                var error2 = this.err;
                if(error2.fullname) this.inError(error2.fullname);
                if(error2.username) this.inError(error2.username);
                if(error2.email) this.inError(error2.email);
                if(error2.password) this.inError(error2.password);
                if(error2.password_confirmation) this.inError(error2.password_confirmation);
                if(error2.address) this.inError(error2.address);
                if(error2.date_of_birth) this.inError(error2.date_of_birth);
                if(error2.phone) this.inError(error2.phone);
                if(error2.gender) this.inError(error2.gender);
                this.userRegister = user;
            })
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventUserError',er[i]);
        },
        alreadyAcc:function(){
            this.$router.push({name:'LoginUser'}); 
        },
        
    }
}
</script>

<style scoped>

/* #register nằm ở phần HIỆU ỨNG BACKGROUND HÌNH TRÒN */

#main{
    position: relative;
    background-color: white;
    width: 45%;
    margin: auto ;
    margin-top: 120px;
    padding:30px;
    border-radius: 6px;
}
form label {
    font-weight: 600;
    color: #F84B2F;
}

/* login google */
#login-google {
    margin-top: 10px;
  }
  .lb-gg{
    color: gray;
    width: 100%;
    margin: auto;
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
    font-size: 12px;
    margin-bottom: 10px;
  }
  .lb-gg label {
    margin: 0px 6px;
  }
  .lb-gg span{
    display: inline-block;
    width: 30%;
    height: 1px;
    background-color: gray;
  }
  #bt-gg {
    display: flex;
    align-content: center;
    align-items: center;
    border:1px solid #0085FF;
    padding-top:6px ;
    color: #0085FF;
    font-weight: 600;
    padding-bottom:6px ;
    width: 60%;
    margin: auto;
    justify-content: center;
    cursor: pointer;
    transition: all 0.5s ease;
    margin-bottom: 20px;
  }
  #bt-gg img {
    width: 10%;
    object-fit: cover;
    margin-right: 10px;
    border-radius: 20px;
    background-color: white;
    border: 2px solid white;
  }
  #bt-gg:hover {
    color: white;
    background-color: #0085FF;
  }
/* login google */
#top-main{
    width: 100%;
    justify-content: center;
    font-weight:bold ;
    font-size: 20px;
    display: flex;
}

/* login-here */
#login-here{
    margin-top: 20px;
    width: 100%;
    justify-content: center;
    display: flex;
    color: gray;
}
#login-here span{
    font-weight: bold;
    margin-left: 10px;
    color: black;
    cursor: pointer;
    transition: all 0.5s ease;
}
#login-here span:hover{
    color:#0085FF;
    text-decoration: underline;
}






/* HIỆU ỨNG BACKGROUND HÌNH TRÒN */
#register{
    background: #0085FF;
    background: -moz-linear-gradient(-45deg, #0085FF 0%, #F20053 100%);
    background: -webkit-linear-gradient(-45deg, #0085FF 0%,#F20053 100%);
    background: linear-gradient(135deg, #0085FF 0%,#F20053 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00adef', endColorstr='#0076e5',GradientType=1 );
    position: relative;
    height: 950px;   /* CHỈNH CHIỀU DÀI CỦA CÁI TO NHẤT RỘNG RA LÀ ĐƯỢC */
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


/* btn Login */
.btn-pers {
    position: relative;
    left: 50%;
    padding: 1em 2.5em;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 2.5px;
    font-weight: 700;
    color: #F84B2F;
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
    background-color: #F84B2F;
    box-shadow: 0px 15px 20px #f7cbc4;
    color: #fff;
    transform: translate(-50%, -7px);
  }
  
  .btn-pers:active {
    transform: translate(-50%, -1px);
  }
   /* btn Login */
  

</style>