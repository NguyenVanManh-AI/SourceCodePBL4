<template>
    <div>
        <!-- <ParticleVue32></ParticleVue32> -->
        Statistical
        <div style="width: 600px;justify-content: center;display: flex;">
            <LineChart/>
        </div>
    </div>
</template>

<script>
import LineChart from './LineChart.vue'

// import ParticleVue32 from "./particle/ParticleVue32.vue";

export default {
    name:"StatisticalAdmin",
    components:{
        LineChart,
        // ParticleVue32
    },
    setup() {
        document.title = "Meta Shop | Statiscal";
    },
    data(){
        return {

        }
    },
    mounted(){

    },
    methods:{

    },
    watch:{

    }
}
</script>

<style scoped>

</style>
