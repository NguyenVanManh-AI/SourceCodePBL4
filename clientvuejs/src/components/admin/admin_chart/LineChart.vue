<template>
  <div >
    <div>
      <canvas id="myChart"></canvas>
    </div>
  </div>
</template>

<script >
  // import {Chart} from 'chart.js'
  import useEventBus from '../../../composables/useEventBus'
  import Chart from 'chart.js/auto';

  export default {
    name: 'LineChart' ,
    data(){
      return{
          datas:[],
          datas2:[],
          labels:[],
      }
    },
    mounted(){
      var ctx = document.getElementById('myChart');
      this.labels = ['January','February','March','April','May','June','July','August','September','October','November','December'];
      this.datas = [6500, 5900, 8000, 81, 5600, 55, 4000,6005, 59, 8000, 8001, 1];

      this.datas2 = [16500, 52900, 8000, 3481, 5600, 8555, 4000,36005, 59, 28000, 98001, 12];

      var data = {
        labels: this.labels,
        datasets: [
          {
            label: 'Revenue',
            data: this.datas1,
            fill: false,
            borderColor: 'green',
            tension: 0.1
          },
          {
            label: 'Import goods',
            data: this.datas2,
            fill: false,
            borderColor: 'rgb(0, 190, 248)',
            tension: 0.1
          }
        ]
      };

      var mc = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
          scales: {
            x : {
              title: {
                display: true,
                text: 'Time'
              },
            },
            y: {
                ticks: {
                    // callback: function(value, index, ticks) {
                    callback: function(value) {
                      return '$' + new Intl.NumberFormat().format(value);
                    }
                },
                title: {
                  display: true,
                  text: 'Money'
                },
            }
          },
          plugins: {
            // title: {
            //     display: true,
            //     text: 'Custom Chart Title',
            //     padding: {
            //         top: 10,
            //         bottom: 30
            //     }
            // }
        }
        }
    });
    const { onEvent } = useEventBus()
    onEvent('eventAdminStatistical',ob=>{
    
      mc.destroy(); // RẤT QUAN TRỌNG , biểu đồ trước đó phải được hủy thì mới tạo lại được biểu đồ mới 
      // https://stackoverflow.com/questions/40056555/destroy-chart-js-bar-graph-to-redraw-other-graph-in-same-canvas
      data = {
          labels: ob.labels_line,
          datasets: [
            {
              label: 'Revenue',
              data: ob.datas_revenue,
              fill: false,
              borderColor: 'green',
              tension: 0.1
            },
            {
              label: 'Import goods',
              data: ob.datas_import,
              fill: false,
              borderColor: 'rgb(0, 190, 248)',
              tension: 0.1
            }
          ]
        };
        mc = new Chart(ctx, {
          type: 'line',
          data: data,
          options: {
            scales: {
              x : {
                title: {
                  display: true,
                  text: 'Time'
                },
              },
              y: {
                  ticks: {
                      callback: function(value) {
                        return '$ ' + new Intl.NumberFormat().format(value);
                      }
                  },
                  title: {
                    display: true,
                    text: 'Money'
                  },
              }
            },
            plugins: {
              // title: {
              //     display: true,
              //     text: 'Custom Chart Title',
              //     padding: {
              //         top: 10,
              //         bottom: 30
              //     }
              // }
          }
          }
      });
    });

  },
}

</script>
