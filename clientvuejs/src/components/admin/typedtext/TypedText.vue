<template>
  <h1 id="typed-text"></h1>
</template>

<script>
import TypeIt from "typeit";
import { onMounted } from "vue";

export default {
  name: "TypedText",
  setup() {
    onMounted(() => {
      new TypeIt("#typed-text", {
        speed: 50,
        lifelike: true,
        cursor: true,
        cursorSpeed: 3000,
        loop: true,
      })
        .type("Welcome to the admin's page ! ")
        .pause(500)
        .delete()
        .type("Now Login to use the system ! ")
        .pause(500)
        .go();
    });
  },
};
</script>

<style scoped>
#typed-text {
  color: #0085FF;
}
</style>