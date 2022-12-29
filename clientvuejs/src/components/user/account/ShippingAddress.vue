<template>
    <div id="profile" >
        <ParticleVue32></ParticleVue32>
        <img v-if="user.url_img != null" :src="url_img">
        <div id="details" class="col-12">
            <form class="col-12 p-0" @submit.prevent="saveAddress">
                <div class="row" >
                    <div class="col-9">
                        <div style="margin-top: 30px;margin-bottom: 20px;color:gray"><i class="fa-solid fa-map-location-dot"></i> SHIPPING ADDRESS</div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-7">
                        <label><i class="fa-solid fa-user-check"></i> Recipient Name</label><input v-model="shipping_address.recipient_name" placeholder="Recipient Name" type="text" class="form-control" >
                    </div>
                    <div class="col-5">
                        <label for="formGroupExampleInput"><i class="fa-solid fa-phone"></i> Number Phone</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend"><div class="input-group-text">+84</div></div>
                            <input type="text" v-model="shipping_address.phone_number" class="form-control"  placeholder="Number Phone">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12"><label><i class="fa-solid fa-location-dot"></i> Address</label><textarea rows="2" v-model="shipping_address.address" placeholder="Address" type="text" class="form-control" ></textarea></div>
                </div>
                <div class="dt1">
                    <div>
                        <button type="submit" class="mt-4 btn-pers" id="login_button" >Save</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="message">
            <Notification></Notification>
        </div>

    </div>
</template>


<script scoped>

import BaseRequest from '../../../restful/user/core/BaseRequest';
import useEventBus from '../../../composables/useEventBus';
import Notification from './../Notification';
import config from '../../../config.js';

import ParticleVue32 from "./../../admin/particle/ParticleVue32.vue";

export default {
    name : "ShippingAddressUser",
    components: {
      Notification,
      ParticleVue32,
    },
    created(){
        document.title = "Meta Shop - Shipping Address"
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
            err:{
                recipient_name:[],
                phone_number:[],
                address:[],
            },
            url_img:'',
            shipping_address:{
                recipient_name:'',
                phone_number:'',
                address:''
            }
        }
    },
    setup(){
      return {

      }
    },
    computed(){

    },
    mounted(){

        this.user = JSON.parse(window.localStorage.getItem('user'));
        if(this.user != null && this.user.url_img != null) this.url_img = config.API_URL +'/'+ this.user.url_img;

        BaseRequest.get('api/shipping-address/get-address?customer_id='+this.user.id)
            .then( (data) =>{
                const { emitEvent } = useEventBus();
                if(data.shipping_address != null) this.shipping_address = data.shipping_address;
                else {
                    emitEvent('eventUserSuccess',data.message);
                }
            }) 
            .catch(()=>{

            })

    },
    methods:{
        saveAddress:function(){
            BaseRequest.post('api/shipping-address/update-or-create?customer_id='+this.user.id,this.shipping_address)
            .then( (data) =>{

                this.shipping_address = data.shipping_address;

                const { emitEvent } = useEventBus();
                emitEvent('eventUserSuccess',data.message);

                // setTimeout(()=>{
                //     window.location=window.location.href;
                // }, 1500);

            }) 
            .catch(error=>{
                this.err = error.response.data;
                var error2 = this.err;
                if(error2.recipient_name) this.inError(error2.recipient_name);
                if(error2.phone_number) this.inError(error2.phone_number);
                if(error2.address) this.inError(error2.address);
            })
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventUserError',er[i]);
        },
    }
}
</script>

<style scoped>
* {
    transition: all 1s ease;
}
#profile{
    position: relative;
    background-color: #F2F4F6;
    padding: 30px 100px;
    padding-bottom: 30px;
    /* height: 800px; */
    min-width: 100%;
}

/* details */
#details{
    width: 100%;
    background-color: #000;
    /* box-shadow: 0px 10px 10px -10px gray; */
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgb(204, 219, 232) -3px -3px 6px 1px inset;
    /* box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px; */
    padding: 20px 40px;
    border-radius: 10px;
    position: relative;
    background-color: white;
    background-color: rgba(255, 255, 255, 0.545);
    /* chọn white sau đó kéo thanh dọc xuống là có màu này */
    font-weight: bold;
    /* background-color: #F84B2F;  */
    /* Fallback color */
    /* background-color: #f84a2f49; */
    /* Black w/opacity/see-through */
}
#profile > img {
    position: absolute;
    right: 0px;
    top: 0px;
    opacity: 0.9;
    min-width: 100%;
    max-height: 100%;
    object-fit: cover;
    filter: blur(8px);
    -webkit-filter: blur(8px);
} 
#details input,textarea{
    background-color: rgba(255, 255, 255, 0.605); 
    color: #0085FF;
    /* background-color: transparent; */
    font-weight: bold;
    /* border: black; */
}
#details label {
    color: #F84B2F;
}


/* btn login */
.btn-pers {
  position: relative;
  left: 50%;
  padding: 1em 2.5em;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  font-weight: 700;
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


</style>
