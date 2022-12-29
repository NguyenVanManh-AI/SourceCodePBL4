<template>
    <div id="administrator">
        <ParticleVue32></ParticleVue32>
        <div id="big">
            <div id="head">
                <div>
                    <div><span @click="home" id="home" class="under">Pages</span> / <span class="under" @click="spwarehouse" id="spwarehouse">Warehouse Import</span>
                    / <span class="under" @click="spimportproduct" id="spimportproduct">Import Product</span></div>
                    <div style="font-weight: bold">Import Product</div>
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
                    <div style="color: gray;font-size: 1rem;padding-top: 10px;"><i class="fa-solid fa-dolly"></i> Import Product</div>
                </div>
                <div class="form-group row">
                    <label class="col-sm col-form-label"><i class="fa-solid fa-building-user"></i> Provider Name <span style="font-size: 10px;color: gray;">(Tip: When select press a key to search faster)</span></label>
                    <select class="custom-select custom-select-lg" v-model="provider_id" style="margin: 0px 15px">
                        <option selected value="0">Open this select menu to select Provider </option>
                        <option v-for="(provider,index) in providers" :key="index" :value="provider.id" >{{provider.name}}</option>
                    </select>
                </div>
                <div id="bodytable">
                    <div v-for="(importPro,index) in importProducts" :key="index" class="importproduct">
                        <div class="row">
                            <div class="col-12">
                                <div class="row"><label class="col-sm-6 col-form-label"># {{index+1}} - Product Name <i class="fa-solid fa-square-check"></i> </label></div>
                                <select class="custom-select custom-select-lg" v-model="importPro.product_id">
                                    <option selected value="0">Open this select menu to select Product</option>
                                    <option v-for="(product,index2) in products" :key="index2" :value="product.id" >{{product.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="row"><label class="col col-form-label " >Quantity</label></div>
                                <div class="row"><div class="col"><input v-model="importPro.quantity" type="number" min="0" class="form-control" placeholder="Quantity (number)"></div></div>
                            </div>
                            <div class="col">
                                <div class="row"><label class="col col-form-label"><i class="fa-solid fa-dollar-sign"></i> Price</label></div>
                                <div class="row"><div class="col"><input v-model="importPro.price" type="number" min="0" class="form-control" placeholder="Price ($)"></div></div>
                            </div>
                            <div class="col">
                                <div class="row"><label class="col col-form-label"><i class="fa-solid fa-percent"></i> Tax</label></div>
                                <div class="row"><div class="col"><input v-model="importPro.tax" type="number" min="0" max="100" class="form-control" placeholder="Tax (%) . 0-100% "></div></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-danger minis" @click="minis(index)"><i class="fa-solid fa-minus"></i></button>
                    </div>     
                    <button type="submit" class="mt-4 btn-pers" @click="addImport" style="margin-left: 30px;padding:10px 16px;top:-26px"><i class="fa-solid fa-plus"></i></button>
                    <div class="dt1">
                        <button type="submit" class="mt-4 btn-pers" @click="fImportProduct" style="margin-left: 100px;top:-56px" ><i class="fa-solid fa-paper-plane"></i> Import </button>
                    </div>    
                </div>
            </div>
        </div>
        <p style="position: absolute;top:1200px;font-size: 1px;">s</p>
        <Notification></Notification>
    </div>
</template>

<script>

import BaseRequest from '../../restful/admin/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
import Notification from './Notification'
import config from '../../config.js'
// import Paginate from 'vuejs-paginate-next';

import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"ImportProduct",
    components:{
        Notification,
        ParticleVue32
        // paginate: Paginate
    },
    setup() {
      document.title = "Meta Shop - Import Product";
    },
    data(){
        return {
            url_img:'',
            products:null,
            providers:null,
            provider_id:0,
            importProducts:[
                {
                    importer_name:null,
                    product_id:0,
                    product_name:null,
                    provider_id:null,
                    provider_name:null,
                    provider_tax_id:null,
                    price:null,
                    quantity:null,
                    tax:null
                }
            ],
            import_id:0,
            index_success:0,
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
            err:{
                importer_name:[],
                product_id:[],
                product_name:[],
                provider_id:[],
                provider_name:[],
                provider_tax_id:[],
                price:[],
                quantity:[],
                tax:[]
            },
            product_success:[0,],

        }
    },
    mounted(){

        this.admin = JSON.parse(window.localStorage.getItem('admin'));
        this.url_img = config.API_URL + '/' + this.admin.url_img; 

        BaseRequest.get('api/imports/getproduct')
        .then( (data) =>{
            // console.log(data);
            this.products = data.product ;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get Product Success !');
        }) 
        .catch(error=>{
            // console.log(error);
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.response.data.message);
        })

        BaseRequest.get('api/imports/getprovider')
        .then( (data) =>{
            // console.log(data);
            this.providers = data.provider ;
            const { emitEvent } = useEventBus();
            emitEvent('eventSuccess','Get Provider Success !');
        }) 
        .catch(error=>{
            // console.log(error);
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.response.data.message);
        })

        BaseRequest.get('api/imports/idmax')
        .then((data)=>{
            this.import_id = data.id_import_max + 1;
        })
        .catch((error)=>{
            const { emitEvent } = useEventBus();
            emitEvent('eventError',error.message);
        })

    },

    methods:{

        home:function(){
            this.$router.push({name:'DashboardAdmin'});
        },
        spwarehouse:function(){
            this.$router.push({name:'WarehouseImport'});
            this.searchad='';
        },
        spimportproduct:function(){
            this.$router.push({name:'ImportProduct'});
        },
        profile:function(){
            this.$router.push({name:'ProfileAdmin'});
        },
        addImport:function(){
            var importPro = {
                    importer_name:null,
                    product_id:0,
                    product_name:null,
                    provider_id:null,
                    provider_name:null,
                    provider_tax_id:null,
                    price:null,
                    quantity:null,
                    tax:null
            }
            this.importProducts.push(importPro);
            // console.log(this.importProducts);
        },
        minis:function(index){
            this.importProducts.splice(index, 1);
        },
        fImportProduct:function(){
            if(this.provider_id == 0){
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Please select Provider !');
            }
            else {

                var provider_name = '';
                var provider_tax_id = '';

                for(var n=0;n<this.providers.length;n++){
                    if(this.providers[n].id == this.provider_id) {
                        provider_name = this.providers[n].name ;
                        provider_tax_id = this.providers[n].tax_id_number;
                    }
                }
                // var ii = 0 ; 
                for(var i=0;i<this.importProducts.length;i++){
                    // ii = i ; 
                    this.importProducts[i].importer_name = this.admin.fullname;
                    this.importProducts[i].provider_id = this.provider_id;
                    this.importProducts[i].provider_name = provider_name;
                    this.importProducts[i].provider_tax_id = provider_tax_id;

                    for(var j=0;j<this.products.length;j++){
                        if(this.products[j].id == this.importProducts[i].product_id){
                            this.importProducts[i].product_name = this.products[j].name;
                        }
                    }

                    BaseRequest.post('api/imports/add?import_id='+this.import_id,this.importProducts[i])
                    .then((data)=>{
                        const { emitEvent } = useEventBus();
                        emitEvent('eventSuccess',data.message);
                        // this.product_success.push(ii); // thêm vào mảng các phần tử đã thành công để sau này xóa 
                        // this.importProducts.splice(i, 1); // nếu thành công thì xóa nó đi 
                        // để lại những cái lỗi (nếu có)
                        setTimeout(()=>{window.location=window.location.href;}, 1500);
                    })
                    .catch((error)=>{
                        this.err = error.response.data;
                        var error2 = this.err;

                        if(error2.importer_name) this.inError(error2.importer_name);
                        if(error2.product_id) this.inError(error2.product_id);
                        if(error2.product_name) this.inError(error2.product_name);
                        if(error2.provider_id) this.inError(error2.provider_id);
                        if(error2.provider_name) this.inError(error2.provider_name);
                        if(error2.provider_tax_id) this.inError(error2.provider_tax_id);
                        if(error2.price) this.inError(error2.price);
                        if(error2.quantity) this.inError(error2.quantity);
                        if(error2.tax) this.inError(error2.tax);
                        // setTimeout(()=>{window.location=window.location.href;}, 1500);
                    })
                }
                // this.rmProduct();
            }
        },
        // rmProduct:function(){
        //     console.log(this.product_success);
        //     for(var i=0;i<this.product_success.length;i++){
        //         var index = this.product_success[i];
        //         this.importProducts.slice(index,1);
        //     }
        // },
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
.dt1 {
    width: 100%;
    align-content: center;
    display: flex;
    justify-content: center;
}
.form-group label {
    font-weight: bold;
}
#spimportproduct{
    color: #3a9efb;
    cursor: pointer;
}
#administrator{
    background-color: #F2F4F6;
    padding: 16px 30px;
    position: relative;
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
#spwarehouse{
    color: #3a9efb;
    cursor: pointer;
}
/* #spwarehouse:hover {
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
    box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
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

/* Import Product */

.importproduct{
    border: 1px solid #0085FF;
    padding: 10px 30px 15px 30px;
    margin-bottom: 20px;
    position: relative;
    background-image: linear-gradient(120deg, white 0%, #edf4fb 100%);
}
.minis{
    position: absolute;
    right:6px;
    top:6px;
}
.importproduct label {
    font-weight: bold;
}
.importproduct .btn{
    transition: all 1s ease;
} 

</style>

<!-- 
    - POST : 
        {
            "importer_name":"Nguyễn Công Cường",             // lấy từ tài khoản đăng nhập 
            "product_id":"2",                                // lấy từ product
            "product_name":"MacBook Air M1",                 // lấy từ product 
            "provider_id":"4",                               // lấy từ provider
            "provider_name":"Apple",                         // lấy từ provider
            "provider_tax_id":"03432201594843",              // lấy từ provider
            "price":"300999",                                // nhập vào 
            "quantity":"10",                                 // nhập vào 
            "tax":"20"                                       // nhập vào 
        }

        - Thêm vào Import -> importer_name, provider_id, provider_name, provider_tax_id . import_date -> server lấy ngày và giờ hiện tại
        - Sau khi Import thành công thì sinh ra id mới (id mới nhất) -> là import_id
        - Thêm vào Import Details -> import_id , product_id , product_name , price , quantity , tax
        - Sau khi thêm Import Details thành công thì cộng thêm quantity vào quantity product


    - Một mảng importProducts sẽ chứa nhiều importProduct
        importProduct : 
            {
                "importer_name":"Nguyễn Công Cường",          
                "product_id":"2",                               
                "product_name":"MacBook Air M1",                  
                "provider_id":"4",                               
                "provider_name":"Apple",                        
                "provider_tax_id":"03432201594843",               
                "price":"300999",                                
                "quantity":"10",                                
                "tax":"20"                                    
            }

        - Mỗi lần click (+) thì importProducts.push(importProduct) => lúc này length sẽ tăng lên => v-for thì sẽ tăng bảng nhập vào lên 
        - Khi post lên thì lặp qua các bảng . bảng nào post thành công thì slice phần tử importProduct nằm trong importProducts đó đi 
        - Bảng nào lỗi thì báo lỗi 

 -->
<!-- 

    Note : 
        + Ta không nên làm theo kiểu search category và một thanh hiển thị như thế 
        => Bởi vì bản chất select của bootstrap hay select thường nó đã tích hợp sẵn việc tìm kiếm cho ta rồi 
        => bằng cách nhập một phím bất kì (chữ cái đầu tiên của nội dung trong option)
        <select class="custom-select custom-select-lg mb-3">
            <option selected>Open this select menu</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
        </select>

        => nhấn O thì nó đến One 
        => nhấn T thì nó đến Two -> Three 
        => lỡ làm bên category rồi thì để thế cũng được => cái khác không làm thế nữa . 

        + Nếu ta để như trên thì sự kiện click từng cái option sẽ không được thực hiện , và nếu ta thêm size thì được 
            <select class="custom-select custom-select-lg mb-3" size="3">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>

            => như trên thì nó hiển thị ra một vùng có chiều cao là 3 hàng 
            => và thực hiện được sự kiện click option 
            => nhưng không còn được như cái trên nữa 

        + Bind dữ liệu tại select sẽ khắc phục được nhược điểm 2 cái trên , ngoài ra có thể dùng phím lên xuống nữa. 
            <select class="custom-select custom-select-lg mb-3" v-model="id">
                <option value="0">Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>

            => click vào dòng nào thì nó sẽ lấy option của dòng đó cho vào id 
        
        => ta sẽ bind cho seletc là product_id khi v-for , và khi khởi tạo phần tử cho mảng thì thuộc tính product_id của 
        phần tử đó để mặc định là 0 để nó select vào dòng có value="0" đó 

    Note : 

        data return : 
        importProducts:[
            {
                importer_name:null,
                product_id:0,
                product_name:null,
                provider_id:null,
                provider_name:null,
                provider_tax_id:null,
                price:null,
                quantity:null,
                tax:null
            }
        ],
        importProduct:{
            importer_name:null,
            product_id:0,
            product_name:null,
            provider_id:null,
            provider_name:null,
            provider_tax_id:null,
            price:null,
            quantity:null,
            tax:null
        },

        => trong mounted() ta sẽ cho nó thêm một bảng đầu tiên để khi người dùng mới vào đã có sẵn 1 cái 
        => khi click add thì thêm phần tử vào mảng 
        => khi click minis thì loại bỏ phần tử có index đó ra khỏi mảng 

        => Ta không được this.importProducts.push(this.importProduct);
        => BỞI VÌ : Đó là gán theo THAM CHIẾU => các mảng khi v-for ra đều mang giá trị như nhau 
            => vì tham chiếu nên khi thay đổi một sản phẩm thì cái khác cũng giống hệt như vậy 

        => Ta phải bởi tạo BIẾN MỚI và thêm vào để nó không liên quan đến cái cũ .  

            // thêm 
            addImport:function(){
                var importPro = {
                    importer_name:null,
                    product_id:0,                       // select vào option có value="0"
                    product_name:null,
                    provider_id:null,
                    provider_name:null,
                    provider_tax_id:null,
                    price:null,
                    quantity:null,
                    tax:null
                }
                this.importProducts.push(importPro);
            },

            // xóa
            minis:function(index){
                this.importProducts.splice(index, 1); // loại bỏ phần tử tại vị trí index 
            },
        
            => trong mounted() cũng tương tự . 



    Note : 
    
        CHÚ Ý : 
        Vì gặp trục trặc ở client nên ta thay đổi cách thức . 

        MỖI LẦN NHẤN NÚT IMPORT 
        Tại client có 2 giai đoạn  
            1. Lấy ra id lớn nhất của bảng import , ví dụ lấy được id = 70 , trả về client id_import_max = 70 => 
                tương lai sẽ có 71 nên 70 + 1 = 71  
            2. Sau đó http://127.0.0.1:8000/api/imports/add?import_id=71 
                + Lặp qua từng phần tử và post lên 

            Bảng import thì kiểm tra có id 71 rồi thì không thêm nữa . 
            Bảng import_detail thì cứ lấy trực tiếp import_id = 71 mà thêm vào . 

        Note : 
            + Ta sẽ không lo lỗi vì bảng import không xóa hay sửa gì cả . chỉ thêm vào . 
            => Nên ta không lo trường hợp id = ...,60,70 => max hiện tại là 70 nhưng xóa dòng 70 đi thì còn 60 
            => lần sau lại post lên 61 => sai 
            => id max sẽ mãi là cố định . Chỉ có tăng thôi . 


        => LỖI NGAY Ở DÒNG GÁN GIÁ TRỊ MỚI CHO import_id 
        => Trong .then() Gán vào thì được , console.log ra giá mới 
        nhưng ngoài .then() thì vẫn cứ mãi giá trị 0 nên thôi . Làm cách khác .

        fImportProduct:function(){
            if(this.provider_id == 0){
                const { emitEvent } = useEventBus();
                emitEvent('eventError','Please select Provider !');
            }
            else {

                var provider_name = '';
                var provider_tax_id = '';

                for(var n=0;n<this.providers.length;n++){
                    if(this.providers[n].id == this.provider_id) {
                        provider_name = this.providers[n].name ;
                        provider_tax_id = this.providers[n].tax_id_number;
                    }
                }

                for(var i=0;i<this.importProducts.length;i++){
                    console.log(abcd);
                    this.importProducts[i].importer_name = this.admin.fullname;
                    this.importProducts[i].provider_id = this.provider_id;
                    this.importProducts[i].provider_name = provider_name;
                    this.importProducts[i].provider_tax_id = provider_tax_id;

                    for(var j=0;j<this.products.length;j++){
                        if(this.products[j].id == this.importProducts[i].product_id){
                            this.importProducts[i].product_name = this.products[j].name;
                        }
                    }
                    BaseRequest.post('api/imports/add?import_id='+this.import_id,this.importProducts[i])
                    .then((data)=>{
                        console.log(data);
                        if(this.import_id == 0) {
                            this.import_id = data.import_id;
                        }
                        const { emitEvent } = useEventBus();
                        emitEvent('eventSuccess',data.message);
                        console.log(i);
                        this.product_success.push(i); // thêm vào mảng các phần tử đã thành công để sau này xóa 
                        // this.importProducts.splice(i, 1); // nếu thành công thì xóa nó đi 
                        // để lại những cái lỗi (nếu có)
                    })
                    .catch((error)=>{
                        this.err = error.response.data;
                        var error2 = this.err;

                        if(error2.importer_name) this.inError(error2.importer_name);
                        if(error2.product_id) this.inError(error2.product_id);
                        if(error2.product_name) this.inError(error2.product_name);
                        if(error2.provider_id) this.inError(error2.provider_id);
                        if(error2.provider_name) this.inError(error2.provider_name);
                        if(error2.provider_tax_id) this.inError(error2.provider_tax_id);
                        if(error2.price) this.inError(error2.price);
                        if(error2.quantity) this.inError(error2.quantity);
                        if(error2.tax) this.inError(error2.tax);
                        // setTimeout(()=>{window.location=window.location.href;}, 1500);
                    })
                }
                this.rmProduct();
            }
        }

 -->