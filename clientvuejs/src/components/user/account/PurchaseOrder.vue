<template>
    <div id="purchaseorder">
        <div id="header">
            <li @click="waitForConfirmation" :class="{border_bt:border_bts[0]}"><i class="fa-brands fa-shopify mr-1"></i> Wait for confirmation</li>
            <li @click="waitingForShipping" :class="{border_bt:border_bts[1]}"><i class="fa-regular fa-circle-check mr-1"></i> Waiting for shipping</li>
            <li @click="delivering" :class="{border_bt:border_bts[2]}"><i class="fa-solid fa-truck-fast mr-1"></i> Delivering</li>
            <li @click="delivered" :class="{border_bt:border_bts[3]}"><i class="fa-solid fa-house-circle-check mr-1"></i> Delivered</li>
            <li @click="cancelled" :class="{border_bt:border_bts[4]}"><i class="fa-solid fa-trash mr-1"></i> Cancelled</li>
        </div>
        <div id="content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>

import useEventBus from '../../../composables/useEventBus'

export default {
    name: "PurchaseOrderUser",
    components: {

    },
    data(){
    return {
            border_bts:[false,false,false,false,false],
        }
    },
    setup() {
        document.title = "Meta Shop - Purchase Order";
    },
    mounted(){
        const { onEvent } = useEventBus()
        onEvent('eventWaitForConfirmation',()=>{
            this.border_bts = [false,false,false,false,false],
            this.border_bts[0] = true;
        })

        var _pathname = window.location.pathname;
        if(_pathname.includes('confirmation')) this.border_bts[0] = true;
        if(_pathname.includes('shipping')) this.border_bts[1] = true;
        if(_pathname.includes('delivering')) this.border_bts[2] = true;
        if(_pathname.includes('delivered')) this.border_bts[3] = true;
        if(_pathname.includes('cancelled')) this.border_bts[4] = true;
    },
    methods: {
    waitForConfirmation:function(){
        this.$router.push({name:'WaitForConfirmation'}); 
        this.reset_bt(0);
        console.log(this.border_bts);
    },
    waitingForShipping:function(){
        this.$router.push({name:'WaitingForShipping'}); 
        this.reset_bt(1);
        console.log(this.border_bts);
    },
    delivering:function(){
        this.$router.push({name:'DeliveringComp'}); 
        this.reset_bt(2);
        console.log(this.border_bts);
    },
    delivered:function(){
        this.$router.push({name:'DeliveredComp'}); 
        this.reset_bt(3);
        console.log(this.border_bts);
    },
    cancelled:function(){
        this.$router.push({name:'CancelledComp'}); 
        this.reset_bt(4);
        console.log(this.border_bts);
    },
    reset_bt:function(index){
        this.border_bts = [false,false,false,false,false];
        this.border_bts[index] = true;
    }
    }
}
</script>
<style scoped>
*{
    list-style: none;
}
#purchaseorder{
    /* padding: 20px; */
    margin-right:20px;
    margin-bottom: 20px;
}
#header{
    display: flex;
    width: 100%;
    height: 50px;
    align-content: center;
    align-items: center;
    /* border: 1px solid gray; */
    border-radius: 3px;
    background-color: white;
    justify-content: space-between;
    margin-bottom: 10px;
}
#header li {
    width: 20%;
    height: 100%;
    font-weight: bold;
    font-size: 16px;
    display: flex;
    align-content: center;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.border_bt {
    border-bottom: 2px solid #218838; 
    /* red */
    color: #218838;
}
#content{
    background-color: white;
    width: 100%;
    min-height: 500px;
}
</style>