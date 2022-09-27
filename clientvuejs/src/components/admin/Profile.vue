<template>
    <div id="profile">
        <div id="head">
            <div id="pr">
                <div>
                    <img src="https://scontent.fhan2-2.fna.fbcdn.net/v/t39.30808-6/301678115_1498049667305947_5629003224171269722_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=174925&_nc_ohc=S8s02QE6SugAX9INJu2&_nc_ht=scontent.fhan2-2.fna&oh=00_AT_PYQy-O06OS1Iwodw1k9YErHEcKV7GFIQtecgtg0D08g&oe=63378486">
                </div>
                <div id="pr2">
                    <div id="name">{{inf.fullname}}</div>
                    <div id="role">{{inf.role}}</div>
                </div>
            </div>
            <div>
                <button type="submit" class="mt-4 btn-pers" id="login_button" >Change Password</button>
            </div>
        </div>
        <div id="details">

            <div style="color:gray"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</div>
            <div style="color:gray;margin-top: 30px;margin-bottom: 20px;"><i class="fa-solid fa-user"></i> USER INFORMATION</div>
            <form class="col-7 p-0" @submit.prevent="saveInfor">
                <div class="dt1">
                    <div><label>Username</label><input v-model="admin.username" placeholder="Username" style="width:260px" type="text" class="form-control" ></div>
                    <div><label>Full Name</label><input v-model="admin.fullname" placeholder="Full Name" style="width:300px" type="text" class="form-control" ></div>
                    <div><label>Date of birth</label><input style="width:200px" type="date" class="form-control" ></div>
                </div>
                
                <div style="color:gray;margin-top: 30px;margin-bottom: 20px;"><i class="fa-solid fa-mobile-screen-button"></i> CONTACT INFORMATION</div>
                <div class="dt1">
                    <div><label>Address</label><input v-model="admin.address" placeholder="Address" style="width:820px" type="text" class="form-control" ></div>
                </div>
                <div class="dt1" >
                    <div><label>Email</label><input v-model="admin.email" placeholder="Email" style="width:280px" type="email" class="form-control" ></div>
                    <div><label>Number Phone</label><input v-model="admin.phone" placeholder="Number Phone" style="width:220px" type="text" class="form-control" ></div>
                    <div><label>Avatar</label><input style="width:260px" type="file" class="form-control" ></div>
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
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'

export default {
    name : "ProfileAdmin",
    components: {
      Notification
    },
    created(){
        document.title = "Meta Shop - Admin Profile"
    },
    data(){
        return{
            admin:{
                id:null,
                fullname:'',
                username:'',
                email: '',
                phone: '',
                age:null,
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
        // console.log(this.admin);

        // var s = "{\"name\":[\"The name field is required.\"],\"email\":[\"The email field is required.\"],\"password\":[\"The password field is required.\"]}";
        // console.log(JSON.parse(s.replace('\ ','')));
    },
    methods:{
        saveInfor:function(){
            let iadmin = JSON.stringify(this.admin);
            BaseRequest.patch('api/auth/update-infor',this.admin)
            .then( () =>{
                // console.log(data);
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Edit Information Success !');

                window.localStorage.setItem('admin',iadmin);
                this.inf.fullname = this.admin.fullname;
                this.inf.role = this.admin.role;
                this.err = null;
            }) 
            .catch(error=>{
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
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
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
}
#head img {
    width: 80px;
    border-radius: 10px;
    margin-right: 20px;
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