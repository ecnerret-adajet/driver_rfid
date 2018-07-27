<template>
    <div class="card mx-auto mb-3">
        <div class="card-header">
            <h5>Driver Entries
                <select class="form-control float-right w-25" v-model="selectedLocation">
                    <option v-for="(location, l) in locations" :key="l" :value="location.id">{{ location.title }}</option>
                </select>
            </h5>

        </div>
        <div class="card-body p-0">
            <ul class="nav nav-tabs m-3" role="tablist">

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#gate" role="tab">Gate Entries</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#queues" role="tab">Queues Entries</a>
                </li>

            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="gate" role="tabpanel">
                    <dashboard-gate-entries :location="selectedLocation"></dashboard-gate-entries>
                </div>
                <div class="tab-pane" id="queues" role="tabpanel">
                    <dashboard-queue-entries :location="selectedLocation"></dashboard-queue-entries>
                </div>

            </div>
        </div>
    </div>
</template>
<script>

import moment from 'moment';
import DashboardGateEntries from './DashboardGateEntries.vue';
import DashboardQueueEntries from './DashboardQueueEntries.vue';
import VueContentPlaceholders from 'vue-content-placeholders';

export default {

    components: {
        DashboardGateEntries,
        DashboardQueueEntries
    },

    data() {
        return {
            loading: false,
            selectedLocation: 1,
            locations: []
        }
    },

    created() {
        this.getDriverqueues()
    },

    methods: {
        getDriverqueues() {
            axios.get('/driver_rfid/public/driverqueues')
            .then(response => this.locations = response.data);
        }
    }
}
</script>
