<template>
    <div>
      Dashboard User
      <input type="text" v-model="n" class="form-control" >
      <button type="button" class="btn btn-primary" @click="plus">Plus</button>
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" >
  <div class="carousel-inner">
    <div class="carousel-item active" >
      <img class="d-block w-100" src="../../assets/logo.png" alt="First slide" >
    </div>
    <div class="carousel-item" >
      <img class="d-block w-100" src="../../assets/logo.png" alt="Second slide" >
    </div>
    <div class="carousel-item" >
      <img class="d-block w-100" src="../../assets/logo.png" alt="Third slide" >
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
    </div>
</template>

<script>

import useEventBus from '../../composables/useEventBus';

export default {
  name: "DashboardUser",
  components: {

  },
  data(){
    return {
      n:0,
    }
  },
  methods: {
    plus:function(){
      this.n++;
      var ob = {
        n:this.n,
        name:'vanmanh'
      }
      const { emitEvent } = useEventBus();
      emitEvent('eventPlus',ob);
    }
  }
}
</script>