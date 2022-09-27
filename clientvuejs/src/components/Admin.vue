<template>
    <div>
        <SidebarMenuAkahon v-if="admin"></SidebarMenuAkahon>
        <router-view></router-view>
    </div>
</template>
<script>

import SidebarMenuAkahon from './admin/Sidebar-menu-akahon.vue';
import useEventBus from './../composables/useEventBus'

export default {
    name: "AdminComp",
    components: {
    SidebarMenuAkahon,
},
    data(){
        return {
            admin:null,
        } 
    },
    methods: {
    
    },
    mounted(){
        this.admin = window.localStorage.getItem('admin');
        const { onEvent } = useEventBus()
        onEvent('eventLogout',()=>{
            this.admin = null;
            this.$router.push({name:"LoginAdmin"});
            window.location = window.location.href;
        })
    }
}
</script>

<style scoped>
    
</style>