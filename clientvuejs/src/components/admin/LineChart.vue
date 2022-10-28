<template>
  <div>
    <canvas id="lineChart" width="400" height="400"></canvas>
  </div>
</template>

<script >
  import {Chart} from 'chart.js'
  export default {
    name: 'LineChart' ,
    mounted(){

    const d = [65, 59, 80, 81, 56, 55, 40]; // để ở ngoài cũng được 
    const ctx = document.getElementById('lineChart');
    const data = {
      labels: ['1','2','3','4','5','6','7'],
      datasets: [{
        label: 'My First Dataset',
        data: d, // để ở ngoài cũng được
        // data : [65, 59, 80, 81, 56, 55, 40]; // như này cũng được 
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        // backgroundColor:'red',
        tension: 0.1
      }]
    };
    const lineChart = new Chart(ctx, {
        // type: 'bar',
        type: 'line',
        data: data,
    });
    lineChart;

  },
}

</script>
