<template>
    <div>
           <div class="row mb-4">
            <div class="col-6">

                 <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-uppercase font-weight-light">
                            <th  class="table-success" scope="col"> 
                                <small>  
                                    <strong>
                                        Drivers in queue # 
                                    </strong>
                                </small> 
                            </th>
                            </tr>
                        </thead> 
                        </table>
                    </div>
                </div>

                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                    <div class="carousel-item" style="height: 850px;" v-for="(loop,q) in Math.ceil(queues.length / 7)" :key="q" :class="{ 'active' : q == 0 }">
                           
                            <table class="table table-bordered table-striped">
                             <tbody>
                                <tr v-for="(queue,y) in queues.slice((loop - 1) * 7, loop *7)" :key="y">
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
                </div>
             
                </div>
                <!-- end carousel -->

            </div>
            <div class="col-6">

                    <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-uppercase font-weight-light">
                            <th  class="table-warning" scope="col"> 
                                <small> 
                                    <strong>
                                    Shipped Drivers 
                                    </strong>
                                </small> 
                            </th>
                            </tr>
                        </thead> 
                        </table>
                    </div>
                </div>

                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                    <div class="carousel-item" style="height: 600px;" v-for="(loop,t) in Math.ceil(todayServed.length / 6)" :key="t" :class="{ 'active' : t == 0 }">    

                        <table class="table table-bordered table-striped">
                        <tbody>
                            <tr v-for="(served,s) in todayServed.slice((loop - 1) * 6, loop *6)" :key="s">
                                <td>

                                    <div class="row" v-if="served.driver">
                                        <div class="col-2 text-center">
                                            <img v-if="served.driver.image" :src="avatar_link + served.driver.image.avatar" class="rounded-circle mx-auto" style="height: 60px; width: auto;"  align="middle">
                                            <img v-else :src="avatar_link + served.driver.avatar" class="rounded-circle mx-auto" style="height: 60px; width: auto;"  align="middle">
                                        </div>
                                        <div class="col-10">
                                            <p class="p-0 m-0">
                                                {{ served.driver.name }}
                                            </p>
                                            <p v-if="served.driver.truck" class="p-0 m-0">
                                                {{ served.driver.truck[0].plate_number }}
                                            </p>
                                            <p v-if="served.driver.hauler" class="p-0 m-0">
                                                {{ served.driver.hauler[0].name }}
                                            </p>
                                        </div>
                                    </div>

                                </td>

                                <td width="25%">
                                <small class="text-uppercase text-muted">
                                        SHIPPED DATE
                                    </small> <br/>
                                    <span>
                                        {{ moment(served.change_date) }}
                                    </span>
                                </td>


                                <td width="20%" class="text-center">
                                    <button class="float-right btn btn-sm btn-outline-danger">
                                            ASSIGNED SHIPMENT
                                    </button>
                                    <span>
                                        {{ served.shipment_number }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="todayServed.length == 0">
                                <td class="text-center" style="padding-top: 30px; padding-bottom: 30px;" colspan="3">
                                    <span class="display-4 text-muted">
                                        Nothing Assigned
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        </table>


                    </div>


                </div>
                </div>




                 <!-- last drive tapped -->
                <table class="table border border-warning table-bordered">
                <thead class="bg-warning">
                    <tr class="text-uppercase font-weight-light">
                    <th scope="col" colspan="3"> <small>  LAST DRIVER TAPPED: </small> </th>
                    </tr>
                </thead> 
                <tbody class="border border-warning">
                    <tr  v-for="(log, i) in lastDriver" v-if="log.drivers.length != 0" :key="i">
                        <td>
                            <div v-for="(driver, d) in log.drivers" :key="d" class="row">
                                <div class="col-3">
                                    <img v-if="driver.image" :src="avatar_link + driver.image.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="middle">
                                    <img v-else :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="middle">
                                </div>
                                <div class="col-9">
                                    <p class="p-0 m-0">
                                        {{ driver.name }} 
                                    </p>
                                    <p v-if="driver.truck" v-for="(truck, t) in driver.truck" :key="t" class="p-0 m-0">
                                        {{ truck.plate_number }} 
                                    </p>
                                    <p v-if="driver.hauler" v-for="(hauler, h) in driver.hauler" :key="h" class="p-0 m-0">
                                        {{ hauler.name }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="small text-uppercase text-muted">
                                QUEUE TIME:
                            </span>
                            <p class="p-0 m-0">
                                {{ moment(log.LocalTime) }}
                            </p>
                        </td>
                    </tr>
                    
                    <tr v-for="(log, z) in lastDriver" :key="z" v-if="log.drivers.length == 0">
                        <td class="text-center" style="padding-top: 30px; padding-bottom: 30px;" colspan="2">
                            <span class="display-4 text-muted">
                                Nothing Here
                            </span>
                        </td>
                    </tr>

                    <tr :class="lastDriverResult.status" v-for="(log, x) in lastDriver" :key="x" v-if="log.drivers.length != 0">
                        <td class="text-center pb-3 pt-3" colspan="2">
                            <span style="font-weight: bold" class="text-small text-uppercase text-dark">
                                {{ lastDriverResult.message }} 
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
                avatar_link: '/storage/',
                queues: [],
                todayServed: [],
                checkSubmission: [],
                totalentries: [],
                lastDriver: [],
                lastDriverResult: [],
            }
        },

        created() {
            this.getQueues()
            this.getTodayServed()
            this.getLastDriver()
            this.getLastDriverResult()
        },

        methods: {
            getQueues() {
                axios.get('/queuesBtn')
                .then(response => this.queues = response.data);
                setTimeout(this.getQueues, 10000);
            },

            getLastDriverResult() {
                axios.get('/conditionFromLastDriver/3')
                .then(response => this.lastDriverResult = response.data);
                setTimeout(this.getLastDriverResult, 2000);
            },

            getTodayServed(){
                axios.get('/servedToday/3') // driverqueue was hardcoded
                .then(response => this.todayServed = response.data);
                setTimeout(this.getTodayServed, 4000);
            },

            getLastDriver() {
                axios.get('/getLastDriverBtn')
                .then(response => this.lastDriver = response.data);
                setTimeout(this.getLastDriver, 2000);
            },
        
            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        }


    }
</script>