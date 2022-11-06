<template>
    <div id="account">
      <div id="side-bar">
        <SideBar></SideBar>
      </div>
      <div id="routerview">
        <router-view></router-view>
      </div>
    </div>
</template>

<script>

import SideBar from './SideBar.vue'

export default {
    name: "AccountUser",
    components: {
        SideBar,
    },
    methods: {
    
    }
}
</script>
<style scoped>
#account{
    background-color: #F7FAFC;
    padding-top:16px;
    display: flex;
}
#routerview {
    margin-left:20px;
    width: 100%; /* để cho các component co nằm trong có width:100% thì tại đây */
    /* nếu sau này có bug gì thì xóa dòng này đi */
    /* chỉnh cái này sẽ lợi hơn vì khi sidebar thu hẹp lại thì các component con mở rộng ra và nhìn rộng hơn */
    /* khi nào mà có style cho component cha mà component con không 100% được chì cho width:100% cho component cha */
}
</style>