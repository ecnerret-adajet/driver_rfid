<template>
<canvas id="topHauler" width="400" height="150"></canvas>
</template>
<script>
import Chart from 'chart.js';
export default {

     props: ['labels','values'],

      mounted(){

            var ctx = document.getElementById("topHauler");
            var topHauler = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: this.labels,
                datasets: [{
                label: ['Top Haulers'],
                data: this.values,
                fill: false,
                backgroundColor: [
                    "rgba(255, 99, 132, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                    "rgba(255, 205, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(201, 203, 207, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                    "rgba(255, 205, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)"
                ],
                borderColor: [
                    "rgb(255, 99, 132)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)",
                    "rgb(54, 162, 235)",
                    "rgb(153, 102, 255)",
                    "rgb(201, 203, 207)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)"
                ],
                borderWidth: 1
            }]
        },
        options: {
            animation:{
                    animateScale:true
            },
            legend: {
                    position: 'top'
            },
        }
        });

	 }// end mounted


}
</script>