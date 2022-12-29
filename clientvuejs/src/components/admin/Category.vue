<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spcategoryadmin" id="spcategoryadmin">Category</span></div>
                    <div style="font-weight: bold" class="ef">Category</div>
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
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-list"></i> Category Table</div>
                    <!-- search  -->
                    <div id="search2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span @click="clicksearch" class="input-group-text" id="validationTooltipUsernamePrepend"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                            <input v-model="searchad" style="width:400px;border-top-right-radius: 6px;border-bottom-right-radius: 6px;" type="text" class="form-control" id="validationTooltipUsername" placeholder="Search Category" aria-describedby="validationTooltipUsernamePrepend" required>
                            <div class="invalid-tooltip">
                                Please choose a unique and valid username.
                            </div>
                        </div>
                    </div>              
                    <!-- search  -->
                    <div id="add_button"><button type="submit" class="mt-4 btn-pers" data-toggle="modal" data-target="#exampleModalAddCategory" ><i class="fa-solid fa-plus"></i></button></div>
                    <!-- Model Add Category -->
                    <div class="modal fade" id="exampleModalAddCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-plus"></i> New Category </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label"><i class="fa-solid fa-list"></i> Name Category</label>
                                        <input v-model="addCate.name" type="text" class="form-control" >
                                    </div>
                                </form>
                            </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="closeAdd" >Close</button>
                                    <button type="button" class="btn btn-outline-primary" @click="addCategory">Add Category</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Model Add Category -->

                </div>

                <!-- Sort by -->
                <div id="sortby">
                    <span style="color: black;font-size: 1rem;padding-top: 10px;padding-right: 10px;"><i class="fa-solid fa-filter"></i> Sorted By  </span> 
                    <button type="button" :class="{clsort:sortlatest,cldf:!sortlatest}" id="esortlatest" @click="fsortlatest()"><i :class="{'fa-solid':true,'fa-arrow-up-short-wide':!sortlatest,'fa-arrow-down-short-wide':sortlatest}"></i> Latest</button>
                    <button type="button" :class="{clsort:sortname,cldf:!sortname}" id="esortname" @click="fsortname()"><i :class="{'fa-solid':true, 'fa-arrow-down-a-z':!sortname,'fa-arrow-down-z-a':sortname}"></i> Name</button>
                </div>
                <!-- Sort by -->

                <div id="bodytable">

                    <div class="table">
                        <div class="item-category" v-for="(category,index) in categorys" :key="index" >
                            <div class="name">
                                <span>
                                    #{{(pageN-1)*21+index+1}}
                                </span>{{category.name}}</div>
                            <div class="gr">
                                <button type="button" class="btn btn-outline-primary" @click="openModel(category.id,category.name)" data-toggle="modal" data-target="#exampleModalEdit"><i class="fa-solid fa-pencil"></i> Edit</button>
                                <button type="button" class="btn btn-outline-danger" @click="deleteCategory(category.id)" ><i class="fa-solid fa-trash"></i> Delete</button>
                            </div>
                        </div>
                    </div>
                    <div id="divpaginate">
                        <paginate class="pag" id="nvm"
                            :page-count="Math.ceil(this.quantity/21)"
                            :page-range="3"
                            :margin-pages="2"
                            :click-handler="clickCallback"
                            :initial-page="this.pageN"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'"
                            :page-class="'page-item'">
                        </paginate>
                    </div>
                </div>
            </div>
        </div>

        <p style="position: absolute;top:1000px;font-size: 1px;">s</p>

        <!-- Model Edit Category -->
        <div class="modal fade" id="exampleModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" @click="resetpage($event)">
            <!-- truyền vào biến $event -->
            <!-- 
                Khi hover vào một cái thì .gr hiện lên (top : 0) -> click vào Delete -> model hiện ra nhưng sau khi tắt model đi 
                thì .gr vẫn chưa về top:60 mà nó vẫn còn top:0 -> fix bằng cách bắt sự kiện khi đóng model thì cho 
                toàn bộ page reset lại cho khỏe . 
                resetpage là sự kiện khi click vào vùng phía ngoài của model . 
                Ngoài ra khi click vào dấu X trên model hay nút Close hay các nút khác nó vẫn thực hiện event này . 
            -->
            <div class="modal-dialog" role="document" id="noclose">
                <div class="modal-content" id="noclose">
                    <div class="modal-header" id="noclose">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="noclose">
                        <label id="noclose" for="recipient-name" class="col-form-label"><i class="fa-solid fa-list"></i> Name Category</label>
                        <input v-model="nameCategory" type="text" class="form-control" id="noclose" style="font-weight: bold;">
                    </div>
                    <div class="modal-footer" id="noclose">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="closeEdit" id="closedl">Close</button>
                        <button type="button" class="btn btn-outline-primary" @click="editCategory">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Model Edit Category -->


        <Notification></Notification>

    </div>
</template>

<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'
import Paginate from 'vuejs-paginate-next';

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"CategoryAdmin",
    components:{
        Notification,
        paginate: Paginate,
        ParticleVue32
    },
    setup() {
        document.title = "Meta Shop - Category"
    },
    data(){
        return {
            url_img:'',
            quantity:null,
            searchad:'',
            pageN:1,
            categorys:null,
            addCate:{
                name:''
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
            domain:config.API_URL,
            idEdit:null,
            nameCategory:null,
            nameCategory2:null,
            err:{
                name:[],
            },
            sortlatest:false,
            sortname:false
        }
    },
    mounted(){

        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('page')) {
            this.pageN = urlParams.get('page');
            if(this.pageN == 0) this.pageN = 1;
            // alert(this.pageN);
        }
        else {
            this.pageN = 1;
        }

        // Nếu như có biến search 
        if(urlParams.has('search')) {
            this.searchad = urlParams.get('search');
            this.pageN = 1;
        }
        
        // tương tự như khi gửi từ client lên server -> boolean về string hết 
        // khi ta lấy từ url xuống cũng vậy . về string hết nên để gán được boolean ta làm như dưới 
        // this.sortlatest = (urlParams.get('sortlatest') === 'true'); this.sortlatest = true nếu biến trên url là 'true'
        // this.sortlatest = false nếu biến trên url là 'fasle' . Nó không bằng chính giá trị 'true' , 'false'
        // mà nó là kết quả của việc đem cái trên url đi so sánh với 'true' nếu 'true' = 'true' => true 
        // tương tự với false 

        // Nếu như có biến sortlatest, sortname  
        if(urlParams.has('sortlatest')) this.sortlatest = (urlParams.get('sortlatest') === 'true');
        if(urlParams.has('sortname')) this.sortname = (urlParams.get('sortname') === 'true');

        BaseRequest.post('api/categorys?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname)
        .then( (data) =>{
            // console.log(data);
            this.quantity = data.quantity;
            this.categorys = data.category.data ;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get All Category Success !');

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
    methods:{

        // sort 
        fsortlatest:function(){
            this.sortlatest = !this.sortlatest;
            this.getdatasort();
        },
        fsortname:function(){
            this.sortname = !this.sortname;
            this.getdatasort();
        },
        getdatasort:function(){
            if(this.pageN == null) this.pageN=1;
            BaseRequest.post('api/categorys?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname)
            .then( (data) =>{
                this.quantity = data.quantity;
                this.categorys = data.category.data ;
            }) 
            .catch(error=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);
            })
        },
        // sort 

        resetpage:function(event){
            // vì ta đặt sự kiện request lại trang cho cái to nhất nên click bất kì cái nào trong cái đó cũng request lại hết 
            // nên để fix tiếp ta làm ntn 
            // ngoại trừ click vào những thằng không liên quan này thì những cái khác click sẽ request lại trang 
            if (event.target.id == 'noclose' || event.target.id == 'exampleModalLabel') {
                return ; 
                // console.log(event.target.tagName);
                // console.log(event.target.id);
                // console.log(event.target.class);
            }
            // trong method vẫn dùng forEach bình thường nha 
            // var grs = document.querySelectorAll('.gr')
            // grs.forEach(egr=>{
            //     egr.style="top:60px;";
            // });
            setTimeout(()=>{window.location=window.location.href;}, 2000);
        },
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spcategoryadmin:function(){
            this.$router.push({name:'CategoryAdmin'});
            this.searchad='';
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        editCategory:function(){
            if(this.nameCategory == this.nameCategory2) return ;
            var s = {name:this.nameCategory}
            BaseRequest.patch('api/categorys/edit/'+this.idEdit,s)
            .then((data)=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                // console.log(data);
            })
            .catch((error)=>{
                console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Edit Category False !');
                this.err = error.response.data;
                var error2 = this.err;
                if(error2.name) this.inError(error2.name);
            })
        },
        openModel:function(id,name){
            this.idEdit = id;
            this.nameCategory = name;
            this.nameCategory2 = name;
        },
        closeEdit:function(){
            this.idEdit = null;
            this.nameEdit = null;
            // alert(this.idDelete);
        },
        deleteCategory:function(id){
            // alert(this.idDelete);
            var closedl = window.document.getElementById('closedl');
            BaseRequest.delete('api/categorys/'+id)
            .then((data)=>{
                // console.log(data);
                closedl.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
            .catch(()=>{
                // console.log(error);
                closedl.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Delete Category False !');
                // setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
        },
        addCategory:function(){
            var closeAdd = window.document.getElementById('closeAdd');
            BaseRequest.post('api/categorys/add',this.addCate)
            .then((data)=>{
                // console.log(data);
                closeAdd.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess',data.message);
                setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
            .catch((error)=>{
                // console.log(error);
                closeAdd.click();
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Add Category False !');
                this.err = error.response.data;
                var error2 = this.err;
                if(error2.name) this.inError(error2.name);
                // setTimeout(()=>{window.location=window.location.href;}, 1500);
            })
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
        clickCallback:function(pageNum){
            BaseRequest.post('api/categorys?page='+pageNum+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                this.pageN = pageNum;
                // let urlParams = new URLSearchParams(window.location.search);
                // urlParams.set('page', this.pageN);
                // window.location.search.set('page',this.pageN);
                this.categorys = data.category.data ;
                const { emitEvent } = useEventBus();
                emitEvent('eventSuccess','Get All Category Success !');

                // setTimeout(()=>{
                //     window.location=window.location.href;
                // }, 1500);
            }) 
            .catch(error=>{
                // console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);

                // Nếu là admin thì không vào được quản trị và không get được thì cho về trang chủ 
                setTimeout(()=>{
                    this.$router.push({name:'DashboardAdmin'}); 
                }, 1500);
            })
        },
        clicksearch:function(){
            window.location = window.location.pathname+"?search="+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname;
        }
    },
    watch:{
        searchad:function(){
            // console.log(this.searchad);
            this.pageN = 1 ;
            BaseRequest.post('api/categorys?page='+this.pageN+'&search='+this.searchad+'&sortlatest='+this.sortlatest+'&sortname='+this.sortname)
            .then( (data) =>{
                // console.log(data);
                this.quantity = data.quantity;
                // let urlParams = new URLSearchParams(window.location.search);
                // urlParams.set('page', this.pageN);
                // window.location.search.set('page',this.pageN);
                this.categorys = data.category.data ;
                // const { emitEvent } = useEventBus();
                // emitEvent('eventSuccess','Get All Admin Success !');

                // setTimeout(()=>{
                //     window.location=window.location.href;
                // }, 1500);
            }) 
            .catch(error=>{
                // console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);
            })
        }
    }
}
</script>

<style scoped>
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

#spcategoryadmin{
    color: #3a9efb;
    cursor: pointer;
}
/* #spcategoryadmin:hover {
    text-decoration: underline;
} */
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
.ef {
    -webkit-animation: pulse 2s cubic-bezier(.4,0,.6,1) infinite!important;
    animation: pulse 2s cubic-bezier(.4,0,.6,1) infinite!important;
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
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    border-radius: 10px;
    align-items: center;
    width: 93%;
    height: 780px;
    font-size: 13px;
    /* border: 10px solid red; */
}

.item-category{
    width:30%;
    height: 60px;
    position: relative;
    overflow: hidden;
    display: inline-block;
    margin-left: 20px;
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    margin-bottom: 10px;
    border-radius: 6px;
}
@import url('https://fonts.googleapis.com/css2?family=PT+Serif&display=swap');
.item-category .name {
    width: 100%;
    height: 100%;
    display: flex;
    align-content: center;
    justify-content: center;
    align-content: center;
    line-height: 60px;
    height: 60px;
    font-size: 16px;
    font-weight: bold;
    letter-spacing:1px;
    word-spacing: 1px;
}
.item-category .name span{
    position: absolute;
    top:0px;
    left:10px;
}
.item-category .gr {
    position: absolute;
    top:60px;
    width: 100%;
    height: 100%;
    display: flex;
    align-content: center;
    justify-content: space-between;
    align-content: center;
    align-items: center;
    padding: 0px 12%;
    height: 60px;
    background-color: white;
    transition: top 0.5s ease;
}
.item-category .gr .btn{
    height: 34px;
    width: 90px;
    font-size: 16px;
}
.item-category:hover .gr{ 
    top:0px
}
/* hover vào thằng cha nào thì thằng con nó chịu ảnh hưởng thôi */


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
.cell1 {
    display: flex;
    align-items: center;
    align-content: center;
}
.cell1 img {
    width: 30px;
    object-fit: cover;
    width: 40px;
    height: 40px;
    border-radius: 20px;
    margin-right: 6px;
}
#exampleModalEdit .btn{
    transition: all 1s ease;
}

#exampleModalAddCategory .btn {
    transition: all 1s ease;
}


#divpaginate{
    display: flex;
    width: 100%;
    justify-content: center;
    position:absolute; /* giúp cho bao nhiêu dòng nó cũng không bị nhảy lên hay nhảy xuống */
    bottom: 20px; /* nếu ít dòng thì nó nhảy lên , nếu nhiều dòng thì nó thụt xuống nên mình cho nó cố định luôn */
    /* margin: 10px 30px; */
    padding-right: 50px;
}

#validationTooltipUsernamePrepend{
    cursor: pointer;
}
#validationTooltipUsernamePrepend:hover{
    color: #0085FF;
}

#sortby{
    margin-top: 10px;
    margin-bottom: 30px;
    width:100%;
    background-color: #F2F4F6;
    padding: 10px 0px 10px 30px;
}
#esortlatest ,#esortname{
    margin-right: 20px;
    outline: none;
    width:auto;
    height: 40px;
    padding: 0px 10px;
    font-size: 18px;
    border-radius: 8px;
    border: 1px solid #0085FF;
}
#esortlatest:hover {
    background-color: #0367c5;
    color: white;
}
#esortname:hover {
    background-color: #0367c5;
    color: white;
}
.cldf{
    background-color:white;
    color: #0085FF;
}
.clsort{
    background-color: #0085FF;
    color: white;
}
</style>
