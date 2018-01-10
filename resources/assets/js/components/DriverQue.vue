<template>
    <div>
           <div class="row mb-4">
            <div class="col">
                <div class="card card bg-light rounded-0">
                    <div class="card-body text-center">
                        <h1  class="card-title pb-1 pt-2 display-3" style="font-weight: 100">
                            {{ totalentries.total }}
                        </h1>
                
                    </div>
                    <div class="card-footer bg-primary text-center">
                    <span style="font-weight: 100" class="text-small text-uppercase text-white">
                        Current In The Plant
                    </span>                            
                    </div>
                </div>
            </div>
            <div class="col">
                     <div class="card bg-light rounded-0">
                        <div class="card-body text-center">
                            <div class="row" v-if="!currentlyServing.length == 0" v-for="serving in currentlyServing">
                                <div class="col-3">
                                    <img :src="avatar_link + serving.driver.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="right">
                                </div>

                                <div class="col-3 text-left">
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
                                  
                                </div>

                                <div class="col-6 text-left">
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
                            <!-- <p class="card-text mt-3 small text-uppercase text-muted">
                                Currently Serving
                            </p> -->
                            
                        </div>
                        <div class="card-footer bg-primary text-center mt-1">
                            <span style="font-weight: 100" class="text-small text-uppercase text-white">
                                Currently Serving
                            </span>                            
                        </div>
                    </div>
            </div>
        </div>


        <div class="row">
            
            <div class="col-6">

            <ul class="list-group rounded-0" style="border-radius: 0 ! important">
                <li class="list-group-item rounded-0" v-for="queue in queues">
                    <div class="row" style="font-size: 70%">
                    <div class="col-2">
                        <img :src="avatar_link + queue.driver_avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                    </div>
                    <div class="col-6">
                        
                        <p class="p-0 m-0">
                             {{ queue.driver_name }} 
                        </p>
                        <p class="p-0 m-0">
                             {{ queue.plate_number }} 
                        </p>
                        <p class="p-0 m-0">
                            <span v-if="queue.hauler == 'NO HAULER'" class="text-danger">
                                    {{ queue.hauler }}
                            </span>
                            <span v-else>
                                    {{ queue.hauler }}
                            </span>
                        </p>

                    </div>
                    <div class="col-4">
                        <span class="small text-uppercase text-muted">
                            QUEUE TIME:
                        </span>
                        <p class="p-0 m-0">
                            {{ moment(queue.log_time.date) }}
                        </p>
                        <span class="small text-uppercase text-muted">
                            LAST DR SUBMISSION:
                        </span>
                         <p class="p-0 m-0">
                            <span v-if="queue.dr_status != 'UNPROCESS'">
                                {{ queue.dr_status.submission_date }}
                            </span>
                            <span v-else>
                                UNPROCESS
                            </span>
                        </p>
                    </div>
                </div>
                </li>
            </ul>


               
            </div>

            <div class="col-6">

                    <ul class="list-group rounded-0" style="border-radius: 0 ! important">
                        <li class="list-group-item rounded-0" v-for="served in todayServed">

                            <div class="row" style="font-size: 70%">
                            <div class="col-2">
                                <img :src="avatar_link + served.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                            </div>
                            <div class="col-6">
                                
                                <p class="p-0 m-0">
                                    {{ served.driver_name }}
                                </p>
                                <p class="p-0 m-0">
                                    {{ served.plate_number }}
                                </p>
                                <p class="p-0 m-0">
                                    {{ served.hauler_name }}
                                </p>

                            </div>

                            <div class="col-4">
                                <button class="float-right btn btn-sm btn-outline-danger">
                                    SERVED
                                </button>
                            </div>


                        </div>
                        </li>
                    </ul>

                    <div class="card mt-4 rounded-0 border border-danger">
                        <div class="card-header bg-danger text-white rounded-0">
                            <span class="small text-uppercase" style="font-weight: 100">
                            Last Driver Tapped:
                            </span>
                        </div>
                        <div class="card-body">

                            <div class="row" style="font-size: 70%" v-for="queue in lastDriver">
                            <div class="col-2">
                                <img :src="avatar_link + queue.driver_avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                            </div>
                            <div class="col-6">
                                
                                <p class="p-0 m-0">
                                    {{ queue.driver_name }} 
                                </p>
                                <p class="p-0 m-0">
                                    {{ queue.plate_number }} 
                                </p>
                                <p class="p-0 m-0">
                                    <span v-if="queue.hauler == 'NO HAULER'" class="text-danger">
                                            {{ queue.hauler }}
                                    </span>
                                    <span v-else>
                                            {{ queue.hauler }}
                                    </span>
                                </p>

                            </div>
                            <div class="col-4">
                                <span class="small text-uppercase text-muted">
                                    QUEUE TIME:
                                </span>
                                <p class="p-0 m-0">
                                    {{ moment(queue.log_time.date) }}
                                </p>
                                <span class="small text-uppercase text-muted">
                                    LAST DR SUBMISSION:
                                </span>
                                <p class="p-0 m-0">
                                    <span v-if="queue.dr_status != 'UNPROCESS'">
                                        {{ queue.dr_status.submission_date }}
                                    </span>
                                    <span v-else>
                                        UNPROCESS
                                    </span>
                                </p>
                            </div>
                        </div>


                        </div>
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
                todayServed: [],
                checkSubmission: [],
                totalentries: [],
                lastDriver: [],
            }
        },

        created() {
            this.getQueues()
            this.getCurrentlyServing()
            this.getTodayServed()
            this.getTotalQueueToday()
            this.getLastDriver()
        },

        methods: {
            getQueues() {
                axios.get('/driver_rfid/public/queues')
                .then(response => this.queues = response.data);
                setTimeout(this.getQueues, 10000);
            },

            getTotalQueueToday() {
                axios.get('/driver_rfid/public/getTotalQueueToday')
                .then(response => this.totalentries = response.data);
                setTimeout(this.getTotalQueueToday, 5000);
            },

            getCurrentlyServing(){
                axios.get('/driver_rfid/public/serving')
                .then(response => this.currentlyServing = response.data);
                setTimeout(this.getCurrentlyServing, 3000);
            },

            getTodayServed(){
                axios.get('/driver_rfid/public/servedToday')
                .then(response => this.todayServed = response.data);
                setTimeout(this.getTodayServed, 4000);
            },

            getLastDriver() {
                axios.get('/driver_rfid/public/getLastDriver')
                .then(response => this.lastDriver = response.data);
                setTimeout(this.getLastDriver, 2000);
            },
        
            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        }


    }
</script>