<template>
    <div>
      Dashboard Admin
    </div>
</template>

<script>
    export default {
      name: "DashboardAdmin",
      components: {

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