<template>
    <div id="profile">
        <ParticleVue32></ParticleVue32>
        <div id="head">
            <div id="pr">
                <div>
                    <img src='../../assets/avatar.png' v-if="this.admin.url_img == null">
                    <img :src=url_img v-if="this.admin.url_img  != null">
                </div>
                <div id="pr2">
                    <div id="name">{{inf.fullname}}</div>
                    <div id="role"><i class="fa-solid fa-shield" style="color:#0085FF"></i> {{inf.role}}</div>
                </div>
            </div>
            <div>
                <button type="button" class="mt-4 btn-pers" data-toggle="modal" data-target="#exampleModal"  >Change Password</button>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa-brands fa-keycdn"></i> Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label"><i class="fa-solid fa-key"></i> Current Password</label>
                                <input v-model="changepw.current_password" type="password" class="form-control" id="current_pw">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label"><i class="fa-solid fa-key"></i> New Password</label>
                                <input v-model="changepw.new_password" type="password" class="form-control" id="new_pw">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label"><i class="fa-solid fa-key"></i> Confrim New Password</label>
                                <input  v-model="changepw.new_password_confirmation" type="password" class="form-control" id="cf_new_pw">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
                        <button type="button" class="btn btn-primary" @click="changeforpw()">Change Password</button>
                    </div>
                    </div>
                </div>
                </div>

            </div>
        </div>
        <div id="details">
            <div style="color:gray"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</div>
            <div style="color:gray;margin-top: 30px;margin-bottom: 20px;"><i class="fa-solid fa-user"></i> USER INFORMATION</div>
            <form class="col-7 p-0" @submit.prevent="saveInfor">
                <div class="dt1">
                    <div><label>Username</label><input v-model="admin.username" placeholder="Username" style="width:260px" type="text" class="form-control" ></div>
                    <div><label>Full Name</label><input v-model="admin.fullname" placeholder="Full Name" style="width:300px" type="text" class="form-control" ></div>
                    <div><label>Date of birth</label><input v-model="admin.date_of_birth" style="width:200px" type="date" format="YYYY MM DD" class="form-control" ></div>
                </div>
                
                <div style="color:gray;margin-top: 30px;margin-bottom: 20px;"><i class="fa-solid fa-mobile-screen-button"></i> CONTACT INFORMATION</div>
                <div class="dt1">
                    <div><label>Address</label><input v-model="admin.address" placeholder="Address" style="width:652px" type="text" class="form-control" ></div>
                    <div>
                        <label>Gender</label>
                        <div style="border:1px solid #ced4da;padding:4px;border-radius:0.25rem;display:flex;height: 38px;">
                            <div class="form-check form-check-inline">
                                <input v-model="admin.gender" class="form-check-input" type="radio" name="inlineRadioOptions" id="male" value="1">
                                <label style="color: #0085FF;" class="form-check-label" for="inlineRadio1">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input v-model="admin.gender" class="form-check-input" type="radio" name="inlineRadioOptions" id="female" value="0">
                                <label style="color: #0085FF;" class="form-check-label" for="inlineRadio2">Women</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="dt1" >
                    <div><label>Email</label><input v-model="admin.email" placeholder="Email" style="width:280px" type="email" class="form-control" ></div>
                    <div><label>Number Phone</label><input v-model="admin.phone" placeholder="Number Phone" style="width:220px" type="text" class="form-control" ></div>
                    <div><label>Avatar</label><input @change="handleOnchange" style="width:260px" type="file" class="form-control" ></div>
                </div>
                <div class="dt1">
                    <div>
                        <button type="submit" class="mt-4 btn-pers" id="login_button" >Save</button>
                    </div>
                </div>
            </form>
        </div>

        <Notification></Notification>

    </div>
</template>


<script scoped>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus';
import Notification from './Notification';
import config from '../../config.js';

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name : "ProfileAdmin",
    components: {
      Notification,
      ParticleVue32
    },
    created(){
        document.title = "Meta Shop - Admin Profile"
    },
    data(){
        return{

            changepw:{
                // id:1,
                current_password:'',
                new_password:'',
                new_password_confirmation:'',
            },
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
            inf:{
                role:'',
                fullname:'',
            },
            err:{
                fullname:[],
                email:[],
                username:[],
                address:[],
                phone:[],
                age:[],
            },
            url_img:''
        }
    },
    setup(){
      return {

      }
    },
    computed(){

    },
    mounted(){

        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.inf.fullname = this.admin.fullname;
        this.inf.role = this.admin.role;
        this.url_img = config.API_URL + '/' + this.admin.url_img; 
        window.document.title = "Meta Shop - "+this.inf.fullname;

        // console.log(this.admin);
        // var s = "{\"name\":[\"The name field is required.\"],\"email\":[\"The email field is required.\"],\"password\":[\"The password field is required.\"]}";
        // console.log(JSON.parse(s.replace('\ ','')));
    },
    methods:{
        saveInfor:function(){
            var iadmin = JSON.stringify(this.admin);
            BaseRequest.patch('api/admin/update-profile',this.admin)
            .then( () =>{
                // console.log(data);

                window.localStorage.setItem('admin',iadmin);

                if(this.image){ // chỉ khi chọn ảnh mới thực hiện hàm upfile 
                    this.upload(); // upfile 
                }

                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Edit Information Success !');

                this.inf.fullname = this.admin.fullname;
                this.inf.role = this.admin.role;
                this.err = null;

                setTimeout(()=>{
                    window.location=window.location.href;
                }, 1500);

            }) 
            .catch(error=>{
                console.log(error);
                this.err = error.response.data;
                var error2 = this.err;

                if(error2.fullname) this.inError(error2.fullname);
                if(error2.username) this.inError(error2.username);
                if(error2.email) this.inError(error2.email);
                if(error2.address) this.inError(error2.address);
                if(error2.phone) this.inError(error2.phone);
                if(error2.age) this.inError(error2.age);
                // this.err = error.reponse.status;
            })
        },
        changeforpw(){
            // console.log(this.changepw);
            BaseRequest.post('api/admin/change-password?id='+this.admin.id,this.changepw)
            .then( () =>{
                // console.log(data);
                var cl = window.document.getElementById('close'); // Nếu thành công thì cho nó tự động đóng form 
                cl.click();

                this.changepw.current_password='';
                this.changepw.new_password='';
                this.changepw.new_password_confirmation='';

                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Change For Password Success !');

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
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
		handleOnchange(e){
			this.image = e.target.files[0];
		},
		upload(){
			const formData = new FormData;
            formData.set('photo',this.image);
			BaseRequest.post('api/admin/upfile?id='+this.admin.id,formData)
			.then( data=>{
                this.admin.url_img = data.link;
                window.localStorage.setItem('admin',JSON.stringify(this.admin)); // cập nhật lại link ảnh (admin.url_img)
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Up Avatar Success !');
			})
			.catch(() => {
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Up Avatar Failse !');
			})
		}
    }
}
</script>

<style scoped>
* {
    transition: all 1s ease;
}
#profile{
    background-color: #F2F4F6;
    padding: 16px 30px;
    height: 800px;
}


#head {
    display: flex;
    justify-content: space-between;
    padding: 10px 40px;
    margin: 10px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    border-radius: 10px;
    align-items: center;
    position: relative;
}
#head img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    margin-right: 20px;
    object-fit: cover;
}

#pr{
    display: flex;
}
#pr2 {
    padding-top: 20px;
}
#name {
    font-weight: bold;
    font-size: 16px;
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

/* details */
#details{
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    padding: 30px 40px;
    border-radius: 10px;
    margin: 30px 30px;
    position: relative;
}
#details label{
    font-weight: bold;
    font-size: 12px;
}
.dt1 input {
    color: #0085FF;
}
.dt1 {
    display: flex;
    margin-bottom: 20px;
}
.dt1 > div {
    margin-right: 30px;
}
#sm {
    display: flex;
    justify-content: flex-start
}
</style>