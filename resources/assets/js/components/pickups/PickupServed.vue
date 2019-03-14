<template>

<div class="row">
    <div class="col">
        <table class="table">
            <thead class="text-muted text-uppercase font-weight-light" style="font-size: 13px">
                <tr>
                    <th>Pickup #</th>
                    <th>Driver Details</th>
                    <th>DO Details</th>
                    <th>Checked Date</th>
                    <th>Truckscale Date</th>
                    <th>Between</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, i) in pickups" :key="i">
                    <td>{{ item.id }}</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>


</template>

<script>
import VueContentPlaceholders from 'vue-content-placeholders';


export default {

    components: {
        VueContentPlaceholders
    },

    data() {
        return {
            pickups: [],
            loading: false,
        }
    },

    mounted() {
        this.loadPickupServed()
    },

    methods: {
        loadPickupServed() {
            this.loading = true;
            axios.get('/driver_rfid/public/api/pickups/served')
            .then(response => {
                console.log('Check response content: ', response.data.data)
                this.pickups = response.data.data
                this.loading = false
            })
        }
    }
}
</script>

