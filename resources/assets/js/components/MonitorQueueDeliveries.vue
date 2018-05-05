<template>
  <div>

        <div class="row mt-4 mb-2">
            <div class="col-3">

                <div class="card">
                    <div class="card-header">
                       <small class="text-uppercase">OPEN FOR SHIPMENT FOR TODAY</small>
                    </div>
                <div class="card-body">

                    <span class="display-3"  v-if="!loadingCount">
                        {{ totalCount.totalOpen }}
                    </span>
                    <span class="display-3" v-if="loadingCount">
                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </span>

                </div>
                </div>
                        
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <small class="text-uppercase">ASSIGNED SHIPMENT FOR TODAY</small>
                    </div>
                <div class="card-body">
                    <span class="display-3"  v-if="!loadingCount">
                        {{ totalCount.totalAssigned }}
                    </span>
                     <span class="display-3" v-if="loadingCount">
                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </span>
                </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <small class="text-uppercase">TRUCKS IN PLANT TODAY</small>
                    </div>
                <div class="card-body">
                    <span class="display-3"  v-if="!loadingCount">
                        {{ totalCount.current_in_plant }}
                    </span>
                     <span class="display-3" v-if="loadingCount">
                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </span>
                </div>
                </div>
            </div>
            <!-- last shipped truck -->
            <div class="col-3">
                <div class="card">
                     <div class="card-header">
                        <small class="text-uppercase">LAST ASSIGNED SHIPMENT</small>
                    </div>
                    <div class="card-body">
                    <span class="text-uppercase">

                         <div class="row" v-for="(serving,s) in lastAssigned" :key="s">
                             <div class="row" v-if="serving.driver">
                             <div class="col-3 text-center">
                                  <img v-if="serving.driver.image" :src="'/driver_rfid/public/storage/' + serving.driver.image.avatar" class="rounded-circle" style="height: 80px; width: auto;"  align="middle">
                                  <img v-else :src="'/driver_rfid/public/storage/' + serving.driver.avatar" class="rounded-circle" style="height: 80px; width: auto;"  align="middle">
                             </div>
                             <div class="col-9">
                                {{ serving.driver.name }} <br/>
                                <span v-if="serving.driver.truck">
                                    {{ serving.driver.truck[0].plate_number }} <br/>
                                </span>
                                <span v-if="serving.driver.hauler">
                                    {{ serving.driver.hauler[0].name }} <br/>
                                </span>
                             </div>
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
                     
               <div class="col-2">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Queue Categories</label>
                         <select name="age" class="form-control" :disabled="isSearching" v-model="selected">
                            <option selected value="1">All Queues</option>
                            <option value="2">Assigned Shipment</option>
                            <option value="3">Open Shipment</option>
                        </select>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Search</label>
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search Driver Name" />
                    </div>
                </div>
                
                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Filter by date</label>
                        <input class="form-control" type="date" v-model="date">
                    </div>
                </div>

             

                <div class="col-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="row">
                            <div class="col text-right" :class="{'pr-0 ' : isSearching }">
                                <button class="btn btn-block" :class="{'btn-primary' : date, 'btn-danger not-allowed' : !date }" :disabled="!date" @click="isSearching = true">Generate</button>
                            </div>
                            <div class="col-3 text-right"  v-if="isSearching">
                                <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" :href="'/driver_rfid/public/exportQueues/' + 1 + '/' + date">Export to Excell</a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

        </div> <!-- end row -->


            <div class="form-row mb-3 mt-1" v-if="isSearching">
                <div class="col text-center p-3 bg-light">
                    <a class="text-dark h5 text-uppercase" style="font-weight: 100" @click="backToLatest()" href="javascript:void(0);">
                        <i class="fa fa-arrow-left"></i> Back to latest
                    </a>
                </div>
            </div>


            <app-deliveries-feed    :search="searchString" 
                                    v-if="selected == 1 && !isSearching">
            </app-deliveries-feed>

            <app-assigned-shipment-deliveries  :search="searchString" 
                                                v-if="selected == 2 && !isSearching">
            </app-assigned-shipment-deliveries>

            <app-open-shipment-deliveries   :search="searchString" 
                                            v-if="selected == 3 && !isSearching">
            </app-open-shipment-deliveries>

            <app-monitor-queue-search :search-string="searchString" 
                                    :queue_id="1" 
                                    :date="searchDate(date)" 
                                    v-if="isSearching">
            </app-monitor-queue-search>

       

    </div><!-- end template -->

</template>
<script>
    import moment from 'moment';
    import DeliveriesFeed from './DeliveriesFeed.vue';
    import AssignedShipmentDeliveries from './AssignedShipmentDeliveries.vue';
    import OpenShipmentDeliveries from './OpenShipmentDeliveries.vue';
    import MonitorQueueSearch from './MonitorQueueSearch.vue'

    export default {

        data() {
            return {
                selected: 1,
                isSearching: false,
                searchString: '',
                lastAssigned: [],
                totalCount: [],
                loadingLastAssigned: false,
                loadingCount: false,
                date: '',
            }
        },

        created() {
            this.getLastAssigned()
            this.getTotalCountDeliveries()
        },

        methods: {
            getLastAssigned() {
                this.loadingLastAssigned = true
                axios.get('/driver_rfid/public/serving/1')
                .then(response => {
                    this.lastAssigned = response.data
                    this.loadingLastAssigned = false
                });
            },

            getTotalCountDeliveries() {
                this.loadingCount = true
                axios.get('/driver_rfid/public/monitor/count')
                .then(response => {
                    this.totalCount = response.data
                    this.loadingCount = false
                });
            },

            backToLatest() {
                this.isSearching = false;
                this.date = null;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            }
        },

        components: {
            appDeliveriesFeed : DeliveriesFeed,
            appAssignedShipmentDeliveries : AssignedShipmentDeliveries,
            appOpenShipmentDeliveries : OpenShipmentDeliveries,
            appMonitorQueueSearch : MonitorQueueSearch
        },

    }

</script>
