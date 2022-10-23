<template>
    <div id="administrator">
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spproductadmin" id="spproductadmin">Product</span>
                        / <span class="under" @click="spaddproductadmin" id="spaddproductadmin">Add Product</span>
                    </div>
                    <div style="font-weight: bold">Product</div>
                </div>
                <div id="search">
                    <div id="pr" @click="profile">
                        <img :src="url_img" v-if="admin.url_img!=null">
                        <img src='../../assets/avatar.png' v-if="admin.url_img==null">
                    </div>
                </div>
            </div>

            <div id="table">
                <div id="toptable">
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-plus"></i> Add Product </div>
                </div>
                <div>
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;margin-bottom: 20px;"><i class='bx bxl-dropbox'></i> PRODUCT INFORMATION</div>
                </div>
                <div id="bodytable">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Product Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Product Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Description" rows="6"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Dimension</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Dimension">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Material</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Product Material">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Price">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Warranty Period</label>
                        <div class="col-sm-4" style="display: flex;">
                            <input type="number" class="form-control" placeholder="1" min="1" max="1000"> <label class="col-sm-2 col-form-label">(Month)</label>
                            <!-- để ý là input có type gì thì nó quy định type đó cho mình luôn , ví dụ email thì phải nhập đủ email
                            hoặc nếu là number thì phải là số không cho nhập chữ -> rất là tiện -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Price">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Category Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="Search Category Name" style="margin-bottom: 10px;">
                            <select multiple class="form-control" >
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>

                </div>                    
            </div>                    
            <p style="position: absolute;top:1200px;font-size: 1px;">s</p>
            <Notification></Notification>
        </div>
    </div>
</template>

<script>

// import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'

export default {
    name:"ProductAdmin",
    components:{
        Notification,
    },
    setup() {
        
    },
    data(){
        return {
            url_img:'',
            pageN:1,
            product:null,
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
            domain:'',
        }
    },
    mounted(){
        this.domain = config.API_URL;
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 
    },

    methods:{
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spproductadmin:function(){
            this.$router.push({name:'ProductAdmin'});
        },
        spaddproductadmin:function(){
            this.$router.push({name:'ProductAdd'});
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
    },
    watch:{
        
    }
}
</script>

<style scoped>
.col-form-label{
    color: black;
    font-weight: bold;
}
#administrator{
    background-color: #F2F4F6;
    padding: 16px 30px;
    position: relative;
}

#search {
    display: flex;
}
#search2{
    margin-right: 100px;
    margin-top: 4px;
    box-shadow: 0px 10px 10px -10px #3b9cf8;
    /* box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1); */
}
#pr {
    line-height: 100%;
    color: #0085FF;
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
}
#home {
    color: #0085FF;
    cursor: pointer;
}
/* #home:hover {
    text-decoration: underline;
} */
#spproductadmin{
    color: #0085FF;
    cursor: pointer;
}
#spaddproductadmin{
    color: #3a9efb;
    cursor: pointer;
}
/* #spproductadmin:hover {
    text-decoration: underline;
} */
.under{
    position: relative;
    padding: 0px 0px;
}
.under::after{
    content: ' ';
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 0;
    height: 2px;
    background:#0085FF;
    transition: width 0.3s;
}
.under:hover::after {
    width: 100%;
    transition: width 0.3s;
}
#big {
    position: relative;
    background-color: white;
    border-radius: 10px;
    align-items: center;
    box-shadow: 0px 10px 10px -10px #0085FF;
    background-color: #0085FF;
    height: 80px;
    margin-top: 50px;
}
#head {
    position: absolute;
    top: -50px;
    display: flex;
    justify-content: space-between;
    padding: 10px 40px;
    margin: 10px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px white;
    border-radius: 10px;
    align-items: center;
    width: 93%;
}

#table {
    top: 40px;
    position: absolute;
    justify-content: space-between;
    padding: 0px 30px;
    margin: 10px 30px;
    background-color: white;
    /* box-shadow: 0px 10px 10px -10px gray; */
    /* box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset; */
    box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
    border-radius: 10px;
    align-items: center;
    width: 93%;
    font-size: 13px;
    /* border: 10px solid red; */
}


#bodytable .btn {
    font-size: 12px;
    justify-content: center;
    margin-left: 0px;
    margin-right: 0px;
}
#table .select{
    font-size: 12px;
    cursor: pointer;
}
#toptable {
    display: flex;
    padding: 30px 0px 10px 0px;
    justify-content: space-between;
    align-items: center;
}

/* btn add admin */
#add_button{
    position:absolute;
    top: 0px;
    right: 0px;
}
#add_button button{
    font-size: 22px;
    display: flex;
    line-height: 100%;
    align-items: center;
    border-radius: 15px;
}
.btn-pers {
  position: relative;
  /* left: 50%; */
  padding: 16px 16px;
  /* font-size: 12px; */
  text-transform: uppercase;
  /* letter-spacing: 2.5px; */
  font-weight: 700;
  /* color: #000; */
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



/* Table */
.btn {
    transition: all 1s ease;
}



</style>
