<template>
<div id="main">
    <ParticleVue33></ParticleVue33>
    <div id="big">
        <div class="container" >
            <div class="alert alert-success" role="alert" v-if="success==true">
                Password reset is successful !<br>
                Please return to the login page and log in !
            </div>
            <form @submit.prevent="resetpw()" autocomplete="on" v-if="success==false">
                <div style="color:#F84B2F;display: flex;width: 100%;justify-content: center;font-weight: 500;font-size: 20px;align-items: center;align-content: center;"><i class="fa-solid fa-repeat"></i> <span style="margin-left: 6px;">Reset Password</span></div><br>
                <div class="input-form"><input type="text" required v-model="resetPassword.password"><div class="underline"></div><label>New Password</label></div><br>
                <button type="submit" class="mt-4 btn-pers" id="login_button" >Reset</button>
            </form>
        </div>
    </div>
    <Notification></Notification>
</div>
</template>

<script>
import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'

import ParticleVue33 from "./../admin/particle/ParticleVue33.vue";

export default {
    name: "ResetPasswordUser",
    components: {
    Notification,
    ParticleVue33
    },
    data(){
        return {
            token:null,
            resetPassword:{
                password:''
            },
            error:null,
            success:false
        }
    },
    mounted(){
        window.document.title='MetaShop - Reset Password User';
        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('token')) {
            this.token = urlParams.get('token');
        }
    },
    methods: {
        resetpw:function(){
            // window.localStorage.setItem('admin',JSON.stringify(this.admin));
            // console.log(this.loginAdmin);
            if(this.token){
                var v = this.resetPassword;
                BaseRequest.put('api/customer/reset-password/'+this.token,this.resetPassword)
                .then( () => {
                    // console.log("login success !");
                    // alert("Đăng nhập thành công !");
                    this.error = null ;
                    this.success = true;
                    const { emitEvent } = useEventBus();
                    emitEvent('eventUserSuccess','Reset Password Success !');
                })
                .catch( error => {
                    this.resetPassword = v; // để nó không reset ô input đi . 
                    this.error = error;
                    console.log(error);

                    const { emitEvent } = useEventBus();
                    emitEvent('eventUserError','Reset password failed !');
                    // console.log(error.response.data.error);
                    // console.log("login false !");
                })
            }
            else {
                const { emitEvent } = useEventBus();
                emitEvent('eventUserError','Token Invalid ! Reset password failed !');
            }
            
        },
    }
}
</script>

<style scoped>
#big {
    position: relative;
}
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
height: 430px;
}
body{
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(to right, #EF629F, #EECDA3);
}
.container{
    margin-top: 60px;
    border-radius: 26px;
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
    color: #F84B2F;
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
    background: #F84B2F;
    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.3s ease;
}
.input-form input:focus ~ .underline:before,
.input-form input:valid ~ .underline:before{
    transform: scaleX(1);
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
    background-color: #F84B2F;
    box-shadow: 0px 15px 20px #fcbab0;
    color: #fff;
    transform: translate(-50%, -7px);
    }

.btn-pers:active {
    transform: translate(-50%, -1px);
}

#pagelogin{
    position:absolute;
    right: 60px;
    top:10px;
}


</style>

