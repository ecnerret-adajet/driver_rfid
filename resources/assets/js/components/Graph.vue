<template>
       <canvas id="myDashboard" width="400" height="50"></canvas>
</template>
<script>
import Chart from 'chart.js';

export default {

	 props: ['labels','values'],

  	 mounted(){

            var ctx = document.getElementById("myDashboard");
            var myDashboard = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
            labels: [''],
            datasets: [{
            label: this.labels[0],
            data: [this.values[0]],
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255,99,132,1)'
            }, {
            label: this.labels[1],
            data: [this.values[1]],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)'
            }]
            },
        options: {
            animation:{
                    animateScale:true
            },
            legend: {
                    position: 'right'
            },
            scales: {
                xAxes: [{
                    stacked: true,
                    gridLines: {
                            display:false
                    }
                }],
                yAxes: [{
                    stacked: true,
                    gridLines: {
                            display:false
                    },
                    ticks: {
                        beginAtZero: true 
                    }
            }]
            }
        }
        });

	 }// end mounted
   
}

</script>