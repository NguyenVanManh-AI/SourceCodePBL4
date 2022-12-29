<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spproductadmin" id="spproductadmin">Product</span>
                        / <span class="under" @click="spdetailsProductadmin" id="spdetailsProductadmin">Product Details</span>
                    </div>
                    <div style="font-weight: bold">Product Details</div>
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
                            <input v-model="detailsProduct.name" type="text" class="form-control" placeholder="Product Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Import Product Images <i class="fa-regular fa-image"></i></label>
                        <div class="col-sm-10" id="uploadimg">
                            <div class="newimg"><FilePicker></FilePicker></div>
                            <div class="preview-box">
                                <p>Old image</p>
                                <div v-for="(image,index) in images" :key="index" class="img-container"> 
                                    <img :src="domain+'/'+image.image_path" class="preview-img" >
                                    <img  src="../../assets/error.png" v-on:click="removeFile(index);" class="close" alt="Remove">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <textarea v-model="detailsProduct.description" class="form-control" id="exampleFormControlTextarea1" placeholder="Description" rows="6"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Dimension</label>
                        <div class="col-sm-8">
                            <input type="text" v-model="detailsProduct.dimension" class="form-control" placeholder="Dimension . Ex: 16.1 x 10.2 x 0.8 inch">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Material</label>
                        <div class="col-sm-8">
                            <input type="text" v-model="detailsProduct.material" class="form-control" placeholder="Material">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-4">
                            <input type="number" v-model="detailsProduct.price" class="form-control" placeholder="Price , max = 999999999999" min="0" max="999999999999">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Warranty Period</label>
                        <div class="col-sm-4" style="display: flex;">
                            <input v-model="detailsProduct.warranty_period" style="width:200px" type="date" format="YYYY MM DD" class="form-control" >
                            <!-- <input type="number" v-model="product_month" class="form-control" placeholder="1" min="1" max="1000"> <label class="col-sm-2 col-form-label">(Month)</label> -->
                            <!-- để ý là input có type gì thì nó quy định type đó cho mình luôn , ví dụ email thì phải nhập đủ email
                            hoặc nếu là number thì phải là số không cho nhập chữ -> rất là tiện -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Category Name</label>
                        <div class="col-sm-8">
                            <div class="input-group" style="margin-bottom: 10px;">
                                <div class="input-group-prepend">
                                <span @click="clicksearch" class="input-group-text" id="search_category_name"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </div>
                                <input v-model="searchad" style="width:400px;border-top-right-radius: 6px;border-bottom-right-radius: 6px;" type="text" class="form-control" placeholder="Search Category Name" required>
                            </div>
                            <select multiple class="form-control" v-model="category_id2" id="selectcate">
                                <option value="null" @click="searchad='Chưa phân loại'" v-if="!(searchad.length>0 && searchad!='Chưa phân loại')">Chưa phân loại</option>
                                <option v-for="(category,index) in categorys" :key="index" :value="category.id" @click="searchad=category.name;fixclick++">{{category.name}}</option>
                            </select>
                        </div>

                    </div>
                </div>     
                <div class="dt1">
                    <button type="submit" class="mt-4 btn-pers" @click="funcUpdateProduct"><i class="fa-solid fa-floppy-disk"></i> Save </button>
                </div>               
            </div>                    
            <p style="position: absolute;top:1400px;font-size: 1px;">s</p>
            <Notification></Notification>
        </div>
    </div>
</template>

<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'
import FilePicker from './FilePicker.vue';

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"ProductDetails",
    components:{
        Notification,
		FilePicker,
        ParticleVue32
    },
    setup() {
        document.title = "Meta Shop - Product"
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
            searchad:'',
            categorys:'',
            uriProduct:'',
            detailsProduct:{
                id: null,
                name: '',
                warranty_period: '',
                description: '',
                category_id: null,
                price: null,
                material: '',
                dimension: '',
                uri: '',
            },
            images:null,
            imgRemove:[],
            category_id2:'',
            err:{
                name:[],
                warranty_period:[],
                description:[],
                category_id:[],
                price:[],
                material:[],
                dimension:[]
            },
            fixclick:0,
        }
    },
    mounted(){
        this.domain = config.API_URL;
        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        BaseRequest.get('api/products/getcategory')
        .then( (data) =>{
            // console.log(data);
            this.categorys = data.category ;
        }) 
        .catch(error=>{
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.response.data.message);
        })

        // console.log(location.pathname.substring(15)); // '/admin/product/a6a24a00bab1dfb36086' -> cắt từ vị trí 15 sẽ được a6a24a00bab1dfb36086
        this.uriProduct = location.pathname.substring(15);

        BaseRequest.get('api/products/'+this.uriProduct)
        .then( (data) =>{
            // console.log(data);
            this.detailsProduct = data.product ;
            document.title = "Meta Shop - Product - "+this.detailsProduct.name; 
            console.log(this.detailsProduct);
            this.images = data.images;

            if(this.detailsProduct.category_id == null) this.category_id2 = 'null'
            else this.category_id2 = (this.detailsProduct.category_id).toString();

            for(var i=0;i<this.categorys.length;i++){
                if(this.detailsProduct.category_id == this.categorys[i].id) {
                    this.searchad = this.categorys[i].name;
                }
            }

            // console.log(this.category_id);
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','View Detail Product Success !');
            // this.category_id = data.product.category_id;
            // console.log(this.category_id);

        }) 
        .catch(error=>{
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.message);
        })

    },

    methods:{
        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spproductadmin:function(){
            this.$router.push({name:'ProductAdmin'});
        },
        spdetailsProductadmin:function(){
            // this.$router.push({name:'ProductDetails'});
            window.location=window.location.href;
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        removeFile:function(index){
            this.imgRemove.push(this.images[index].id); // mảng lưu các id của ảnh bị xóa 
            this.images.splice(index, 1); // bỏ ảnh đó ra khỏi mảng các ảnh cũ 
            // console.log(this.imgRemove.toString());
        },
        funcUpdateProduct:function(){
            // lỗi khi mới vào mà click save luôn nó sẽ lỗi vì liên quan đến mảng 
            // nếu click vào thì category_id2 sẽ thành mảng các id 
            // nếu không click thì nó chỉ là một chuỗi nên ví dụ 45 => lấy phần tử đầu tiên sẽ là 4 nên nó lỗi -> fix lại 
            if(this.fixclick==0){
                if(this.category_id2 != "null" && this.category_id2.length>0) this.detailsProduct.category_id = this.category_id2;
            }
            else {
                if(this.category_id2 != "null" && this.category_id2.length>0) this.detailsProduct.category_id = this.category_id2[0];
            }
            // if(this.category_id2 != "null" && this.category_id2.length>0) this.detailsProduct.category_id = this.category_id2[0]; 
            // ví dụ id = 45 => this.category_id2[0]; = '4' (mất số 5 đi)

            // console.log(this.detailsProduct);
            // ta sẽ chuyển mảng các id của ảnh thành string bằng toString() của js 
            // khi nối ex : [2] => '2' , [2,3] => '2,3' => khi tách sẽ tách thông quan kí tự ',' 
            // sau đó trên server , dùng $array = explode(',', $string); để tách chuỗi string thành mảng array 

            // và nên /id thay vì  /uri , /uri rồi dùng where $uri thì chỉ có thêm lâu -> chủ yếu làm màu thôi 
            // uri là để thực hiện phương thức get và hiển thị ra trên client sẽ hợp lý hơn 
            // còn lại nên find(id) cho chặc chẽ  
            var s = this.imgRemove.toString();
            BaseRequest.patch('api/products/update/'+this.detailsProduct.id+'?removeimages='+s,this.detailsProduct)
            .then( (data) =>{

                const { emitEvent } = useEventBus();
                console.log(this.detailsProduct.id);
                emitEvent('eventUpfile',this.detailsProduct.id);
                setTimeout(()=>{emitEvent('eventResetUpfile');}, 2000);

                // this.detailsProduct.name='';
                // this.detailsProduct.warranty_period='';
                // this.detailsProduct.description='';
                // this.detailsProduct.category_id='null';
                // this.detailsProduct.price=0;
                // this.detailsProduct.material='';
                // this.detailsProduct.dimension='';

                // this.searchad = '';

                emitEvent('eventSuccess',data.message);

                setTimeout(()=>{window.location=window.location.href;}, 2000);

            }) 
            .catch(error=>{
                console.log(error);
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Edit Product false !');
                this.err = error.response.data;
                var error2 = this.err;
                if(error2.name) this.inError(error2.name);
                if(error2.warranty_period) this.inError(error2.warranty_period);
                if(error2.description) this.inError(error2.description);
                if(error2.category_id) this.inError(error2.category_id);
                if(error2.price) this.inError(error2.price);
                if(error2.material) this.inError(error2.material);
                if(error2.dimension) this.inError(error2.dimension);
            })
        },
        inError:function(er){
            const { emitEvent } = useEventBus();
            for(var i=0;i<er.length;i++) emitEvent('eventError',er[i]);
        },
    },
    watch:{
        searchad:function(){
            BaseRequest.get('api/products/getcategory?search='+this.searchad)
            .then( (data) =>{
                this.categorys = data.category ;
            }) 
            .catch(error=>{
                const { emitEvent } = useEventBus();
                emitEvent('eventError',error.response.data.message);
            })
        }
    }
}
</script>

<style scoped>

#selectcate option:hover {
    background-color: #0085FF;
    color:white;
}

/* HIỂN THỊ CÁC ẢNH CŨ */
.img-container{
    position: relative;
    display: inline-block;
    padding: 10px;
}
.preview-img{
    width: 80px;
    padding: 10px;
    border: 1px dotted #b3b3b39e;
}
.preview-box{
    display: inline-block;
    width: 55%;
    background-color: white;
    border-radius: 20px;
    padding: 10px 10px;
    min-height: 300px;
    min-width: 300px;

    background-color: #ffffff;
    -webkit-box-shadow: 2px 4px 20px 2px #dadada;
    box-shadow: 2px 4px 20px 2px #dadada;
    margin-left: 20px;
}
.close{
    position: absolute;
    top: 0px;
    right: 0px;
    width: 20px;
}

/* HIỂN THỊ CÁC ẢNH CŨ */

/* HIỂN THỊ ẢNH MỚI */
#uploadimg{
    display: flex;
}
.newimg{
    min-width: 10%;
}
/* HIỂN THỊ ẢNH MỚI */
.dt1 input {
    color: #0085FF;
}
.dt1 {
    width: 100%;
    align-content: center;
    display: flex;
    margin-bottom: 20px;
    justify-content: center;
    padding-left: 90px;
}

#search_category_name{
    cursor: pointer;
}
#search_category_name:hover {
    color: #0085FF;
}
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
#spdetailsProductadmin{
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
    background-image: linear-gradient(120deg, white 0%, #d0e7ff 100%);
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
  padding: 16px 36px;
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
