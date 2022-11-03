<template>
  <h1 id="typed-text2"></h1>
</template>

<script>
import TypeIt from "typeit";
import { onMounted } from "vue";

export default {
  name: "TypedText2",
  setup() {
    onMounted(() => {
      new TypeIt("#typed-text2", {
        speed: 50,
        lifelike: true,
        cursor: true,
        cursorSpeed: 3000,
        loop: true,
      })
        .type(" Fast Shipping and Free Ship ! ")
        .pause(500)
        .delete()
        .type(" Barter within 7 days ! ")
        .pause(500)
        .go();
    });
  },
};
</script>

<style scoped>
#typed-text2 {
  display: inline;
  color: white;
  width: 100%;
}
</style>