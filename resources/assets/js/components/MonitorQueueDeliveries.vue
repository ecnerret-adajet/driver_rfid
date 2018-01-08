<template>
  <div>


           <div class="row mb-4 mt-3">
            <div class="col">
                <div class="card card bg-light rounded-0">
                <div class="card-body text-center">
                    <h1 class="card-title pb-1 pt-2 display-2" style="font-weight: 100">
                        {{ queues.length }}
                    </h1>
                    <p class="card-text small text-uppercase text-muted pt-5">
                        Current In The Plant
                    </p>
                </div>
                </div>
            </div>
            <div class="col">
                <div v-if="!loadingServing">
                    <div class="card bg-light rounded-0">
                        <div class="card-body text-center">
                            <div class="row" v-if="!currentlyServing.length == 0"  v-for="serving in currentlyServing">
                                <div class="col-6">
                                    <img :src="avatar_link + serving.driver.avatar" class="rounded-circle" style="height: 150px; width: auto;"  align="right">
                                </div>
                                <div class="col-6 text-left">
                                    <span class="small text-muted text-uppercase">
                                        Driver Name:
                                    </span><br/>
                                    <span>
                                        {{ serving.driver.name }}
                                    </span><br/>
                                    <span class="small text-muted text-uppercase">
                                        Plate Number:
                                    </span><br/>
                                    <span v-for="truckx in serving.driver.truck">
                                        {{ truckx.plate_number }}
                                    </span><br/>
                                    <span class="small text-muted text-uppercase">
                                        Hauler Name:
                                    </span><br/>
                                    <span v-for="haulerx in serving.driver.hauler">
                                        {{ haulerx.name }}
                                    </span>
                                </div>
                            </div>
                            <h1 v-if="currentlyServing == 0" class="card-title text-muted pb-1 pt-2 pb-5 display-2" style="font-weight: 100">
                                OPEN
                            </h1>
                            <p class="card-text mt-3 small text-uppercase text-muted">
                                Currently Serving
                            </p>
                        </div>
                    </div>
                </div>
                <div v-if="loadingServing">
                      <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">

                <div class="table-responsive">
                    <table class="table" width="100%" cellspacing="0" style="font-size: 70%">
                        <thead>
                            <tr style="text-transform:uppercase">
                                <th></th>
                                <th>Driver Name</th>
                                <th>Plate Number</th>
                                <th>Hauler</th>
                                <th>Date/Time</th>
                                <th>LAST DR SUBMISSION</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody v-if="!loadingDeliveries">
                                <tr v-for="queue in queues">
                                        <td>
                                             <img :src="avatar_link + queue.driver_avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </td>
                                        <td>
                                            {{ queue.driver_name }} 
                                        </td>
                                        <td>
                                            {{ queue.plate_number }}
                                        </td>
                                        <td>
                                            <span v-if="queue.hauler == 'NO HAULER'" class="text-danger">
                                                 {{ queue.hauler }}
                                            </span>
                                            <span v-else>
                                                 {{ queue.hauler }}
                                            </span>
                                           
                                        </td>
                                        <td>
                                            {{ moment(queue.log_time.date) }}
                                        </td>
                                        <td>
                                            <span v-if="queue.dr_status" v-for="(status, index) in queue.dr_status">
                                                <span v-if="index == 0">
                                                    {{ status.submission_date }}
                                                </span>                                            
                                            </span>
                                        </td>
                                        <td>
                                             <span v-if="!queue.on_serving">
                                                 <a class="btn btn-success btn-sm" href="javascript:void(0);" data-toggle="modal" :data-target="'#servingModal-'+ queue.driver_id">
                                                     OPEN
                                                 </a>
                                             </span>
                                             <span v-else>
                                                 <button class="btn btn-danger btn-sm disabled">
                                                     NOW SERVING
                                                 </button>
                                             </span>
                                        </td>

                                    </tr>
                                    
                        </tbody>
                        <tbody v-if="loadingDeliveries">
                            <tr>
                                <td colspan="7 text-center">
                                    <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;">
                                        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                        </svg>	
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div v-for="queue in queues">

            <!-- serving modal -->
            <div class="modal fade" :id="'servingModal-' + queue.driver_id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="driverModalLabel">Serving Truck</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="POST" :action="'/driver_rfid/public/storeCurrentlyServing/'+ queue.driver_id">
                        <input type="hidden" name="_token" :value="csrf">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div><!-- end modal -->

        </div><!-- end modal loop -->


    </div><!-- end template -->

</template>
<script>
    import moment from 'moment';

    export default {

        data() {
            return {
                avatar_link: '/driver_rfid/public/storage/',
                queues: [],
                currentlyServing: [],
                checkSubmission: [],
                loadingDeliveries: false,
                loadingServing: false,
                csrf: '',
            }
        },

        mounted() {
            this.csrf = window.Laravel.csrfToken;
        },

        created() {
            this.getQueues()
            this.getCurrentlyServing()
        },

        methods: {
            getQueues() {
                this.loadingDeliveries = true
                axios.get('/driver_rfid/public/monitor/deliveries')
                .then(response => {
                    this.queues = response.data
                    this.loadingDeliveries = false
                });
            },

            getCurrentlyServing(){
                this.loadingServing = true
                 axios.get('/driver_rfid/public/serving')
                .then(response => {
                    this.currentlyServing = response.data
                    this.loadingServing = false
                });
            },
        
            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        },



    }

</script>