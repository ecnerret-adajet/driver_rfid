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
                        <!-- {{ totalCount.totalOpen }} -->
                        0
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
                        <!-- {{ totalCount.totalAssigned }} -->
                        0
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
                        <!-- {{ totalCount.current_in_plant }} -->
                        0
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
                        <small class="text-uppercase">LAST ASSIGNED SHIPMENT</small>
                    </div>
                    <div class="card-body">
                    <span class="text-uppercase">

                         <div class="row" v-for="serving in lastAssigned">
                             <div class="col-3 text-center">
                                 <span v-if="serving.driver.image">
                                  <img :src="'/driver_rfid/public/storage/' + serving.driver.image.avatar" class="rounded-circle" style="height: 80px; width: auto;"  align="middle">
                                 </span>
                                 <span v-else>
                                  <img :src="'/driver_rfid/public/storage/' + serving.driver.avatar" class="rounded-circle" style="height: 80px; width: auto;"  align="middle">
                                 </span>
                             </div>
                             <div class="col-9">
                                {{ serving.driver.name }} <br/>
                                <span v-for="truckx in serving.driver.truck">
                                    {{ truckx.plate_number }} <br/>
                                </span>
                                <span v-for="haulerx in serving.driver.hauler">
                                    {{ haulerx.name }} <br/>
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
        </div>

        <div class="form-row mb-2 mt-3">
                     
                <div class="col-2">
                    <div class="form-group">
                        <select name="age" class="form-control disabled" v-model="selected">
                            <option selected value="1">All Queues</option>
                            <option value="2">Assigned Shipment</option>
                            <option value="3">Open Shipment</option>
                        </select>
                    </div>
                </div>

                <div class="col-10">
                    <div class="form-group">
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search Driver Name" />
                    </div>
                </div>

        </div> <!-- end row -->


            <app-deliveries-feed-btn    :search="searchString" 
                                    v-if="selected == 1">
            </app-deliveries-feed-btn>

            <app-assigned-shipment-deliveries-btn  :search="searchString" 
                                                v-if="selected == 2">
            </app-assigned-shipment-deliveries-btn>

            <app-open-shipment-deliveries-btn   :search="searchString" 
                                            v-if="selected == 3">
            </app-open-shipment-deliveries-btn>

       

    </div><!-- end template -->

</template>
<script>
    import moment from 'moment';
    import DeliveriesFeedBtn from './DeliveriesFeedBtn.vue';
    import AssignedShipmentDeliveriesBtn from './AssignedShipmentDeliveriesBtn.vue';
    import OpenShipmentDeliveriesBtn from './OpenShipmentDeliveriesBtn.vue';

    export default {

        data() {
            return {
                selected: 1,
                searchString: '',
                lastAssigned: [],
                totalCount: [],
                loadingLastAssigned: false,
                loadingCount: false,
            }
        },

        created() {
            this.getLastAssigned()
            this.getTotalCountDeliveries()
        },

        methods: {
            getLastAssigned() {
                this.loadingLastAssigned = true
                axios.get('/driver_rfid/public/serving')
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
            }
        },

        components: {
            appDeliveriesFeedBtn : DeliveriesFeedBtn,
            appAssignedShipmentDeliveriesBtn : AssignedShipmentDeliveriesBtn,
            appOpenShipmentDeliveriesBtn : OpenShipmentDeliveriesBtn,
        },

    }

</script>
