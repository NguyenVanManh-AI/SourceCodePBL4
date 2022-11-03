<template>
    <div id="footer">
        Footer
      <div id="top-line" style="height:600px">
      </div>
    </div>
</template>
<script>

export default {
    name: "FooterUser",
    components: {

    },
    methods: {
    
    }
}
</script>
<style scoped>
#footer {
  width: 100%;
  border: 1px solid red;
  background-color: rgb(97, 178, 244);
  position: relative; /* chỉ cần để postion là fix được cái background */
  /* z-index: 1; */ /* còn không cần để z-index vì để thêm cũng chẳng được gì mà còn che đi những cái được hiện ra của header
  khi hover vào , vì cái nào cũng z-index mà footer nằm dưới nên ưu tiên hơn nên đè lên  */
}

</style>