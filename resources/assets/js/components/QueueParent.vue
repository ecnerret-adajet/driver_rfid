<template>
  <div>

     <div class="row mt-4 mb-2">
            <div class="col-3 text-center  mt-4">

                    <span class="display-3" v-if="noShipment">
                        {{ noShipment }}
                    </span>
                    <span class="display-3" v-if="!noShipment">
                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>
                    </span>
                   <p class="mt-4">
                        <small class="text-uppercase">OPEN FOR SHIPMENT WITHIN 24Hours</small>
                    </p>


            </div>
            <div class="col-2 text-center mt-4">

                    <span class="display-3"  v-if="withShipment">
                        {{ withShipment }}
                    </span>
                     <span class="display-3" v-if="!withShipment">
                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>
                    </span>
                  <p class="mt-4">
                        <small class="text-uppercase">ASSIGNED SHIPMENT WITHIN 24Hours</small>
                    </p>

            </div>
            <div class="col-3 text-center  mt-4">

                    <span class="display-3"  v-if="!loadingCount">
                        {{ totalCount.current_in_plant }}
                    </span>
                     <span class="display-3" v-if="loadingCount">
                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>
                    </span>
                   <p class="mt-4">
                         <small class="text-uppercase">TRUCKS ARRIVED TODAY</small>
                    </p>

            </div>
            <!-- last shipped truck -->
            <div class="col-4">
                <div class="card">
                     <div class="card-header">
                        <small class="text-uppercase">LAST ASSIGNED SHIPMENT</small>
                    </div>
                    <div class="card-body">
                    <span class="text-uppercase">

                        <div class="row" v-if="lastAssigned.length != 0">
                             <div class="col-3 text-center">
                                <img :src="'/driver_rfid/public/storage/' + lastAssigned.avatar" class="rounded-circle" style="height: 80px; width: auto;"  align="middle">
                             </div>
                             <div class="col-9">
                                 {{ lastAssigned.driver_name }}  <br/>
                                <span v-if="lastAssigned.plate_number">
                                      {{ lastAssigned.plate_number }}  <br/>
                                </span>
                                <span v-if="lastAssigned.hauler_name">
                                     {{ lastAssigned.hauler_name }} <br/>
                                </span>
                             </div>
                         </div>

                         <div class="row" v-if="lastAssigned.length == 0">
                            <div class="col text-center">
                                <span class="display-3 text-muted">
                                    OPEN
                                </span>
                            </div>
                        </div>


                    </span>
                </div>
                </div>
            </div>
            <!-- end last served truck -->
        </div>

        <div class="form-row mb-2 mt-3">

                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Search</label>
                        <input type="text" class="form-control"  v-model="search" placeholder="Search Driver Name, Plate Number" />
                    </div>
                </div>

                <!-- <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Filter by date</label>
                        <input class="form-control" type="date" v-model="date">
                    </div>
                </div> -->

                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Filter by date</label>
                        <!-- <input class="form-control" type="date" v-model="date"> -->
                        <select class="form-control" v-model="selected">
                            <option selected value="1">Within 24 hours</option>
                            <option value="2">Older than 24 hours</option>
                        </select>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Filter by Category</label>
                        <select class="form-control" v-model="filter">
                            <option selected value="all">All</option>
                            <option value="with-shipment">With Shipment</option>
                            <option value="no-shipment">Open Shipment</option>
                        </select>
                    </div>
                </div>

        </div> <!-- end row -->

            <app-queue-search   v-if="selected == 1"
                                @withShipment="withShipment = $event"
                                @noShipment="noShipment = $event"
                                :filter="filter"
                                :location="location"
                                :search="search">
            </app-queue-search>

            <app-queue-entries-older v-if="selected == 2"
                                :filter="filter"
                                :location="location"
                                :search="search"
                                @withShipment="withShipment = $event"
                                @noShipment="noShipment = $event">
            </app-queue-entries-older>

    </div><!-- end template -->

</template>
<script>
    import moment from 'moment';
    import QueueSearch from './QueueSearch.vue';
    import QueueEntriesOlder from './QueueEntriesOlder.vue';

    export default {

        props: ['location'],

        components: {
            appQueueEntriesOlder : QueueEntriesOlder,
            appQueueSearch : QueueSearch,
        },

        data() {
            return {
                filter: 'all',
                selected: 1,
                search: '',
                lastAssigned: [],
                totalCount: [],
                withShipment: '',
                noShipment: '',
                loadingLastAssigned: false,
                loadingCount: false,
                date: moment(new Date()).format('YYYY-MM-DD'),
            }
        },

        created() {
            this.getLastAssigned()
            this.getTotalCountDeliveries()
        },

        methods: {
            getLastAssigned() {
                this.loadingLastAssigned = true
                axios.get('/driver_rfid/public/lastDriverTapped/' + this.location)
                .then(response => {
                    this.lastAssigned = response.data
                    this.loadingLastAssigned = false
                });
            },

            getTotalCountDeliveries() {
                this.loadingCount = true
                axios.get('/driver_rfid/public/getQueueStatus/' + this.location)
                .then(response => {
                    this.totalCount = response.data
                    this.loadingCount = false
                });
            },

            backToLatest() {
                this.selected = false;
                this.date = null;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            }
        },
    }

</script>
