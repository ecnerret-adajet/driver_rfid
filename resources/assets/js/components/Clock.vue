<template>

<h4 id="clock" class="clock">loading ...</h4>

</template>
<script>
import moment from 'moment';
export default {
    mounted() {
        $('#clock').fitText(1.3);
        function update() {
        $('#clock').html(moment().format('D. MMMM YYYY H:mm:ss'));
        }setInterval(update, 1000);
    }
}

</script>