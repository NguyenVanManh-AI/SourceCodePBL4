<template>
    <div>
      <ParticleVue32></ParticleVue32>
      Dashboard Admin
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

</style>