<template>
  <div>
    UserOrder
    <li v-for="(product,index) in user_order" :key="index">
        <img :src="domain+'/'+product.image_path" >
        {{product.product_name}}
        {{product.buy_number}}
        {{product.price}}
    </li>
  </div>
</template>

<script>
import config from '../../config.js';

export default {
    name:"UserOrder",
    data(){
      return{
        domain:'',
        user_order:[],
      }
    },
    mounted(){
        this.user_order = JSON.parse(window.localStorage.getItem('user_order'));
        this.domain = config.API_URL;
    }

}
</script>

<style scoped>

</style>