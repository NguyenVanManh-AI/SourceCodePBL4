<template>
  <div id="big_user_order">
    <div id="big">
      <div class="row pl-12 pb-6" style="font-weight:bold">
        <div class="col-6"><input type="checkbox" v-model="mainStatus" @change="allStatus(mainStatus)"> <span style="margin-left:20px">Product</span></div>
        <div class="col-2">Price($)</div>
        <div class="col-2">Quantity</div>
        <div class="col-2">Control</div>
      </div>
      <hr>
      <div v-if="user_order != null">
        <div class="row pl-12 pb-3 pt-3 pr_border" v-for="(pr,index) in user_order" :key="index">
          <div class="col-6 pr_infor">
            <input type="checkbox" v-model="pr.status" @change="saveLocal()">
            <div class="pr_img col-4" >
              <img :src="API_URL+'/'+pr.image_path"> 
            </div>
            <div class="pr_name col-8">
              <div class="alert alert-danger" role="alert" v-if="pr.buy_number > pr.quantity">The number of products is only {{pr.quantity}}, please choose less quantity !</div>
              <span><i class="fa-solid fa-feather-pointed"></i> {{pr.product_name}}</span><br>
            </div>
          </div>
          <div class="col-2 d-flex d-flex align-items-center pr_num">{{new Intl.NumberFormat().format(pr.price)}}$</div>
          <div class="col-2 d-flex d-flex align-items-center pr_num">
            <button type="button" style="color:gray;margin-right:6px;outline:none" @click="pr.buy_number++;saveLocal();"><i class="fa-solid fa-square-plus"></i></button>
            <input type="number" disabled v-model="pr.buy_number" min="0" class="form-control col-5">
            <button type="button" style="color:gray;margin-left:6px;outline:none" @click="if(pr.buy_number > 0) pr.buy_number--;saveLocal();"><i class="fa-solid fa-square-minus"></i></button>
          </div>
          <div class="col-2 d-flex d-flex align-items-center"><button type="button" @click="user_order.splice(index, 1);saveLocal();" class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i> Delete</button></div>
        </div>
      </div>
      <hr>
      <div class="row pl-12 pb-3 pt-3 ">
        <div class="col-6 d-flex align-items-center">
            <button type="button" class="btn btn-danger" @click="deleteAll()"><i class="fa-solid fa-trash"></i> Delete</button>
        </div>
        <div class="col-4 d-flex align-items-center total">
          Total payment (<span>{{new Intl.NumberFormat().format(totalProduct)}}</span> product) : <span>{{new Intl.NumberFormat().format(totalPrice)}}$</span>
        </div>
        <div class="col-2 d-flex align-items-center">
            <button type="button" class="btn btn-outline-success" @click="buyNow()"><i class="fa-solid fa-cart-shopping"></i> Buy Now </button>
        </div>

      </div>
    </div>
    <Notification></Notification>
  </div>
</template>

<script>
import config from '../../config.js';
import Notification from './Notification'
// import FilePicker from './FilePicker.vue';
import BaseRequest from '../../restful/user/core/BaseRequest';
import useEventBus from '../../composables/useEventBus'
// import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"UserOrder",
    components:{
      Notification,
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

        domain:'',
        user_order:null,
        products:[],
        API_URL : '',
        totalProduct:0,
        totalPrice:0,
        mainStatus:false,
      }
    },
    setup() {
        document.title = "Meta Shop - Order"
    },
    mounted(){

      this.user = JSON.parse(window.localStorage.getItem('user'));

      this.API_URL = config.API_URL; 
      this.user_order = JSON.parse(window.localStorage.getItem('user_order'));
      this.domain = config.API_URL;

      // để cải thiện thêm thì chỉ cần lấy ra số lượng của những sản phẩm mình đang order 
      // không lấy ra hết database , không lấy ảnh ra => cải thiện sau (chưa kể còn xét đến trường hợp đang order thì 
      // trên server xóa sản phẩm đó đi nữa)

      BaseRequest.get('api/dashboard-customer/all-products3')
      .then( (data) =>{
        this.products = data.product.data ;
      }) 
      .catch(()=>{
        
      })

      if(this.user_order != null){
        for(var i=0;i<this.user_order.length ;i++){
          // kiểm tra số lượng 
          for(var j=0;j<this.products.length ;j++){
            if(this.user_order[i].product_id == this.products[j].product_id) this.user_order[i].quantity == this.products[j].quantity;
          }
          // không nên reset check , người dùng click vào cái nào thì lưu lại vào local như thế => đó là tính năng  
          if(this.user_order[i].status == true) {
            this.totalProduct++;
            this.totalPrice += this.user_order[i].price * this.user_order[i].buy_number;
          }
        }
      }
    },
    methods:{
      saveLocal:function(){
        window.localStorage.setItem('user_order',JSON.stringify(this.user_order));
        const { emitEvent } = useEventBus();
        emitEvent('eventUserOrder');

        // tính lại số lượng và tiền 
        if(this.user_order != null){
          this.totalProduct = 0 ;
          this.totalPrice = 0 ;
          for(var i=0;i<this.user_order.length ;i++){
            if(this.user_order[i].status == true) {
              this.totalProduct++;
              this.totalPrice += this.user_order[i].price * this.user_order[i].buy_number;
            }
          }
        }
      },
      allStatus:function(mainStatus){
        for(var i=0;i<this.user_order.length;i++){
          if(mainStatus == true) this.user_order[i].status = true;
          else this.user_order[i].status = false;
        }
        this.saveLocal();
        console.log(this.user_order);
      },
      deleteAll:function(){
        // for(var i=0;i<this.user_order.length;i++){
        //   if(this.user_order[i].status == true) this.user_order.splice(i, 1);
        // }
        this.user_order = this.user_order.filter(function(pr){
          if(pr.status == true) return false ; 
          else return true; 
        });
        this.saveLocal();
      },
      buyNow:function(){
        // khi tính tiền (total) thì tính sao kệ nó , dù có quá số lượng hay không cũng kệ 
        // chỉ khi click Buy Now mình mới báo . Buy Now sẽ lượt qua tất cả và lấy sản phẩm có status là true  
        // Sản phẩm có status là true nhưng sản phẩm vượt quá số lượng thì báo ra 
        // => người dùng có 2 lựa hoặc là bỏ chọn , hoặc là giảm số lượng => chặc chẽ 
        // (từ đây ta thấy nếu người dùng bỏ chọn thì OK tất nhiên sẽ được , hoặc là người dùng giảm số lượng cx OK)
        // Nếu không có lỗi thì gửi lên server số lượng mua và id của sản phẩm tương ứng  

        const { emitEvent } = useEventBus();
        var buyNow=[];
        for(var i=0;i<this.user_order.length;i++){
          if(this.user_order[i].status == true) {
            if(this.user_order[i].buy_number > this.user_order[i].quantity) {
              emitEvent('eventUserError','The number of '+this.user_order[i].product_name+' cannot exceed !');
              return ; // tất nhiên chỉ cần một cái lỗi thôi thì cx dừng lại 
            }
            else {
                var pr = {
                  product_id:this.user_order[i].product_id,
                  buy_number:this.user_order[i].buy_number,
                  product_name:this.user_order[i].product_name,
                  price:this.user_order[i].price,
                }
                buyNow.push(pr);
            }
          }
        }

        // kiểm tra xem đăng nhập hay chưa 
        if(window.localStorage.getItem('user') == null) {
          emitEvent('eventUserError','You need to login !');
          setTimeout(()=>{
            this.$router.push({name:'LoginUser'}); 
            window.location=window.origin + window.pathname ;
            return ; 
          }, 1000);
          return ; 
        }

        // không có gì hết thì thôi 
        if(buyNow.length == 0) {
          return ; 
        }

        var buy_data = {
          buy_now : buyNow,
          id_user : this.user.id,
          totalPrice : this.totalPrice,
        }

        BaseRequest.post('api/customer-order/buy-now',buy_data)
        .then( () =>{
          // console.log(data);
          // sau khi order thành công thì xóa 
          this.deleteAll();
          emitEvent('eventUserSuccess','Buy Product Success !');
        }) 
        .catch(error=>{
          // console.log(error.response.data);
          emitEvent('eventUserError',error.response.data.error);
        })

        // nếu buy thành công thì lượt qua các phần tử có id đó trong user_order và xóa nó đi 
        // ta không làm giống như bên productDetail là nối chuỗi rồi lên server tách chuỗi nữa . 
        // ta gửi nguyên cả object như sau 
          // {
          //     "buy_now":[
          //         {"id":71,"buy_number":18},
          //         {"id":72,"buy_number":16}
          //     ],
          //     "id_user":12
          // }
          // => lên server forEach ra sau đó chuyển từ array sang object rồi lưu vào database 
        // => từ nay trở đi sẽ làm ntn nếu gặp phải việc cần gửi dữ liệu kiểu này lên server 
      }
    }
}
</script>

<style scoped>
#big_user_order{
  padding: 20px;
  background-color: #F0F2F5;
}
#big {
  background-color: white;
  padding: 30px 0px;
  padding-bottom: 0px;
}

.pr_infor {
  display: flex;
  align-content: center;
  align-items: center;
}
.pr_img img {
  width: 80%;
  height: 80%;
  object-fit: contain;
}
.pr_name span{
  color: #0085FF;
  font-weight: bold;
  font-size: 20px;
}
.pr_num {
  /* color: #218838; */
  color: red;
  font-weight: bold;
}
.pr_border {
  border-bottom: 2px solid #F0F2F5;
}
.total {
  font-weight: bold;
}
.total span {
  color: #218838;
  font-size: 20px;
  margin: 0px 10px;
}

/* Đúng ra là mỗi lần click + hoặc - là mỗi lần gọi từ server lên để check vì có thể trong lúc mình đang 
xem thì admin thêm số lượng hoặc người dùng khác mua hết số lượng => nhưng làm vậy khá là mệt 
=> tạm thời chỉ gọi một lần khi vào trang => sau này cải thiện sau */
</style>
