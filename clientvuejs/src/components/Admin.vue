<template>
    <div>
        <SidebarMenuAkahon v-if="admin" id="noprintpdf"></SidebarMenuAkahon>
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

<style>
/* THÊM CÁI backgound ở component login sau đó tự nhiên nó lỗi nút X (bị lệch qua một bên) ở tất cả các Modal ở tất cả
các component luôn nên để fix lại thì cho vào style tại cái component to nhất của admin là Admin.vue */
/* và không để chế độ scoped để nó ảnh hưởng đến tất cả các component */
.modal-dialog > .modal-content > .modal-header > button {
    margin-right: 10px;
    outline: none;
    margin-top: 0px;
}
/* @media print{
    #noprintpdf { 
        display: none;
        margin: 0px;
        padding: 0px; 
    }
} */
</style>