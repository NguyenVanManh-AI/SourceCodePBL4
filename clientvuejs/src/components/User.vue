<template>
    <div>
        <HeaderUser></HeaderUser>
        <router-view></router-view>
        <FooterUser></FooterUser>
    </div>
</template>
<script>

import HeaderUser from './user/HeaderUser';
import FooterUser from './user/FooterUser';

export default {
    name: "UserComp",
    components: {
        HeaderUser,
        FooterUser
    },
    methods: {
        
    },
    mounted(){
        // this.$router.push({name:'DashboardUser'}); 
    }
}
</script>
<style >
/*scroll*/
#side-content {
    overflow: hidden;  /*ẩn cả x và y , chỉ hiện khi di chuột qua */
    /*overflow-x: hidden;*/
    /*overflow-y: hidden;*/
    /*x là thanh ngang , y là thanh dọc , */
}
#side-content:hover{
    overflow-y: scroll; /*ẩn hết , chỉ hiện khi di chuột qua ,x vẫn ẩn , y hiện khi di chuột qua */
}
::-webkit-scrollbar { /*toàn bộ ô cuộn*/
  width: 13px;
  
}

/* Track */
::-webkit-scrollbar-track { 
  background: white;  /*màu nền của ô cuộn */
}
 
/* Handle */
::-webkit-scrollbar-thumb { /*thanh cuộn nằm trong ô cuộn*/
    background: #F84B2F; 
    /* background: #888;   */
    border-radius: 10px;
    border: 3px solid transparent; /*HAY*/
    background-clip: content-box;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover { /*thanh cuộn nằm trong ô cuộn khi hover vào */
    /* background: #0085FF;  */
    background: #F84B2F; 
    /*background: #555; */
    border-radius: 10px;
    border: 3px solid transparent;
    background-clip: content-box;
}
/*scroll*/

</style>