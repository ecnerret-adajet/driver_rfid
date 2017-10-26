<template>
<canvas id="topHauler" width="400" height="50"></canvas>
</template>
<script>
import Chart from 'chart.js';
export default {

     props: ['labels','values'],

      mounted(){

            var ctx = document.getElementById("topHauler");
            var topHauler = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: this.labels,
                datasets: [{
                data: this.values,
                backgroundColor: [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56",
                    "#8e44ad",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71"
                ],
                hoverBackgroundColor: [
                    "#FF6384",
                    "#36A2EB",
                    "#FFCE56",
                    "#8e44ad",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71",
                    "#2ecc71"
                ]
            }]
        },
        options: {
            animation:{
                    animateScale:true
            },
            legend: {
                    position: 'right'
            },
        }
        });

	 }// end mounted


}
</script>