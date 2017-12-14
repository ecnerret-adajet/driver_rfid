<template>
    <div>


           <div class="row mb-4">
            <div class="col">
                <div class="card">
                <div class="card-body text-center">
                    <h1 class="card-title" style="font-weight: 100">
                        {{ queues.length }}
                    </h1>
                    <p class="card-text">
                        Current In The Plant
                    </p>
                </div>
                </div>
            </div>
            <div class="col">
                     <div class="card">
                <div class="card-body text-center">
                    <h1 class="card-title" style="font-weight: 100">
                        0
                    </h1>
                    <p class="card-text">
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
                                <th>DR SUBMISSION</th>
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
                                             <span v-if="queue.driver_status == 1">
                                                 <button class="btn btn-success btn-sm disabled">
                                                     ACTIVE
                                                 </button>
                                             </span>
                                             <span v-else>
                                                 <button class="btn btn-danger btn-sm disabled">
                                                     INACTIVE
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
        },

        methods: {
            getQueues() {
                axios.get('/driver_rfid/public/queues')
                .then(response => this.queues = response.data);
                setTimeout(this.getQueues, 2000);
            },
        
            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        },



    }
</script>