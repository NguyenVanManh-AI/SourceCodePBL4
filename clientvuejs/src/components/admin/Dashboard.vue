<template>
    <div id="big">
      <ParticleVue32></ParticleVue32>
      <div id="main">
        Dashboard Admin
      </div>
    </div>
</template>

<script>
import ParticleVue32 from "./particle/ParticleVue32.vue";

    export default {
      name: "DashboardAdmin",
      components: {
        ParticleVue32
      },
      setup() {
        document.title = "Meta Shop | Dashboard";
      },
      methods: {

      },
      mounted(){
        // if(!window.localStorage.getItem('admin')){
        //     this.$router.push({name:"LoginAdmin"});
        // }
      }
    }
</script>
    
<style scoped>
#big{
    background-color: #F2F4F6;
    padding: 16px 30px;
    height: 800px;
}
#main {
    padding: 10px 40px;
    margin: 10px 30px;
    background-color: white;
    box-shadow: 0px 10px 10px -10px gray;
    border-radius: 10px;
    align-items: center;
    position: relative;
    min-height: 530px;
}
</style>