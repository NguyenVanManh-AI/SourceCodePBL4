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
}
</style>