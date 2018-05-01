<template>
    <div>
           <div class="row mb-4">
            <div class="col-6">
 <table class="table table-bordered table-striped">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  Queue # </small> </th>
            <th scope="col"> <small>  Driver Details </small> </th>
            <th scope="col"> <small>  Recorded Time /Date </small> </th>
            </tr>
        </thead> 
        <tbody>
            <tr v-for="queue in queues">
                <td width="15%" class="text-center">
                    <span class="display-4">
                     {{ queue.queue_number }}
                    </span> 
                </td>
                <td>
                    <div class="row">
                        <div class="col-3">
                        <img :src="avatar_link + queue.driver_avatar" class="rounded-circle mx-auto align-middle" style="height: 80px; width: auto;"  align="middle">
                        </div>
                        <div class="col-9">
                            {{ queue.driver_name }} <br/>
                            {{ queue.plate_number }} <br/>
                            <span v-if="queue.hauler == 'NO HAULER'" class="text-danger">
                                    {{ queue.hauler }}
                            </span>
                            <span v-else>
                                    {{ queue.hauler }}
                            </span>
                        </div>
                    </div>
                </td>
                <td>
                    <small class="text-uppercase text-muted">
                        LAST DR SUBMISSION
                    </small> <br/>
                     <span v-if="queue.dr_status != 'UNPROCESS'">
                        {{ queue.dr_status.submission_date }}
                    </span>
                    <span v-else>
                        UNPROCESS
                    </span>
                     <br/>
                    <small class="text-uppercase text-muted">
                        TAPPED IN QUEUE
                    </small><br/>
                     {{ moment(queue.log_time.date) }}
                </td>
            </tr>
             <tr v-if="queues.length == 0">
                <td class="text-center" style="padding-top: 30px; padding-bottom: 30px;" colspan="3">
                    <span class="display-4 text-muted">
                        ......
                    </span>
                </td>
            </tr>
        </tbody>
        </table>

            </div>
            <div class="col-6 ">
                     <div class="card bg-light rounded-0">
                        <div class="card-body text-center">
                            <div class="row" v-if="!currentlyServing.length == 0" v-for="(serving, i) in currentlyServing" :key="i">
                                <div class="col-3">

                                <span v-if="serving.driver.image">
                                    <img :src="avatar_link + serving.driver.image.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="right">
                                </span>
                                <span v-else>
                                    <img :src="avatar_link + serving.driver.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="right">
                                </span>
                             
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
                             <h1 v-if="currentlyServing == 0" class="card-title text-muted pt-2  display-3" style="font-weight: 100">
                                OPEN
                            </h1>
                            <!-- <p class="card-text mt-3 small text-uppercase text-muted">
                                Currently Serving
                            </p> -->
                            
                        </div>
                        <div class="card-footer bg-primary text-center mt-1">
                            <span style="font-weight: 100" class="text-small text-uppercase text-white">
                                RECENTLY ASSIGNED SHIPMENT
                            </span>                            
                        </div>
                    </div>

  <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-uppercase font-weight-light">
                    <th scope="col"> <small>  Driver Details </small> </th>
                    <th scope="col"> <small>  Status</small> </th>
                    </tr>
                </thead> 
                <tbody>
                    <tr v-for="served in todayServed">
                        <td>
                            <div class="row">
                                <div class="col-2 text-center">
                                    <img :src="avatar_link + served.avatar" class="rounded-circle mx-auto" style="height: 60px; width: auto;"  align="middle">
                                </div>
                                <div class="col-10">
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
                            </div>
                        </td>
                        <td width="20%">
                            <button class="float-right btn btn-sm btn-outline-danger">
                                    ASSIGNED SHIPMENT
                            </button>
                        </td>
                    </tr>
                    <tr v-if="todayServed.length == 0">
                        <td class="text-center" style="padding-top: 30px; padding-bottom: 30px;" colspan="2">
                            <span class="display-4 text-muted">
                                Nothing Assigned
                            </span>
                        </td>
                    </tr>
                </tbody>
                 </table>


                <table class="table border border-warning table-bordered">
                <thead class="bg-warning">
                    <tr class="text-uppercase font-weight-light">
                    <th scope="col" colspan="3"> <small>  LAST DRIVER TAPPED: </small> </th>
                    </tr>
                </thead> 
                <tbody class="border border-warning">
                    <tr :class="{ 'table-danger' : queue.availability == 0 }"  v-for="(queue, i) in lastDriver" :key="i">
                        <td width="15%" class="text-center">
                            <span class="display-4">
                            {{ queue.queue_number }}
                            </span> 
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-3">
                                    <img :src="avatar_link + queue.driver_avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="middle">
                                </div>
                                <div class="col-9">
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
                            </div>
                        </td>
                        <td>
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
                        </td>
                    </tr>
                    <tr v-if="lastDriver.length == 0">
                        <td class="text-center" style="padding-top: 30px; padding-bottom: 30px;" colspan="3">
                            <span class="display-4 text-muted">
                                Nothing Here
                            </span>
                        </td>
                    </tr>
                    <tr :class="{ 'table-danger' : queue.availability == 0 }" v-if="queue.availability == 0"  v-for="(queue, i) in lastDriver" :key="i" colspan="3"  style="padding-top: 5px; padding-bottom: 5px;">
                        <td colspan="3" class="text-center"> 
                             <span class="text-uppercase">
                               Driver is deactivated
                            </span>
                        </td>
                    </tr>
                </tbody>
                </table>




            </div>
        </div>


        <div class="row">
            
            <div class="col-6">

       
        

               
            </div> <!-- end col 6 -->

            <div class="col-6">

               
            </div> <!-- end col 6 -->


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
            // this.getTotalQueueToday()
            this.getLastDriver()
        },

        methods: {
            getQueues() {
                axios.get('/driver_rfid/public/queues')
                .then(response => this.queues = response.data);
                setTimeout(this.getQueues, 10000);
            },

            // getTotalQueueToday() {
            //     axios.get('/driver_rfid/public/getTotalQueueToday')
            //     .then(response => this.totalentries = response.data);
            //     setTimeout(this.getTotalQueueToday, 3500);
            // },

            getCurrentlyServing(){
                axios.get('/driver_rfid/public/serving/1')
                .then(response => this.currentlyServing = response.data);
                setTimeout(this.getCurrentlyServing, 3000);
            },

            getTodayServed(){
                axios.get('/driver_rfid/public/servedToday/1') // driverqueue id was hardcoded 
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