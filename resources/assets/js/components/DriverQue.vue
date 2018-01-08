<template>
    <div>


           <div class="row mb-4">
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
                     <div class="card bg-light rounded-0">
                        <div class="card-body text-center">
                            <div class="row" v-if="!currentlyServing.length == 0" v-for="serving in currentlyServing">
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
                        <tbody>
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
                                                 <a class="btn btn-success btn-sm" href="javascript:void(0);">
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

                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import moment from 'moment';

    export default {

        data() {
            return {
                avatar_link: '/driver_rfid/public/storage/',
                queues: [],
                currentlyServing: [],
                checkSubmission: []
            }
        },

        created() {
            this.getQueues()
            this.getCurrentlyServing()
        },

        methods: {
            getQueues() {
                axios.get('/driver_rfid/public/queues')
                .then(response => this.queues = response.data);
                setTimeout(this.getQueues, 2000);
            },

            getCurrentlyServing(){
                axios.get('/driver_rfid/public/serving')
                .then(response => this.currentlyServing = response.data);
                setTimeout(this.getQueues, 5000);
            },
        
            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        },



    }
</script>