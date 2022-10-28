<template>
    <div>
        Statistical
        <div style="width: 600px;justify-content: center;display: flex;">
            <LineChart/>
        </div>
    </div>
</template>

<script>
import LineChart from './LineChart.vue'

export default {
    name:"StatisticalAdmin",
    components:{
        LineChart
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
