<template>
    <div>
           <div class="row mb-4">


          <div class="col-6">

                 <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-uppercase font-weight-light">
                            <th  class="table-warning" scope="col">
                                <small>
                                    <strong>
                                        List of Drivers On Queue for Shipment
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
                                                <img :src="avatar_link + queue.avatar" class="rounded-circle mx-auto align-middle" style="height: 80px; width: auto;"  align="middle">
                                            </div>
                                            <div class="col-9">
                                                {{ queue.driver_name }} <br/>
                                                <span v-if="queue.plate_number">
                                                    {{ queue.plate_number }} <br/>
                                                </span>
                                                <span class="text-danger" v-else>
                                                    NO TRUCK <br/>
                                                </span>
                                                <span v-if="queue.hauler_name">
                                                    {{ queue.hauler_name }} <br/>
                                                </span>
                                                <span class="text-danger" v-else>
                                                    NO HAULER
                                                </span>
                                                 <keep-alive>
                                                <sequence-status :item="queue"></sequence-status>
                                                 </keep-alive>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-uppercase text-muted">
                                            LAST DR SUBMISSION
                                        </small> <br/>
                                        <span>
                                            {{ queue.isDRCompleted }}
                                        </span>
                                        <br/>
                                        <small class="text-uppercase text-muted">
                                            TAPPED IN QUEUE
                                        </small><br/>
                                        {{ moment(queue.LocalTime) }}
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
                                    List Drivers with Shipment
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

                                <td width="35%">
                                <small class="text-uppercase text-muted">
                                        SHIPPED DATE
                                    </small> <br/>
                                    <span>
                                        {{ moment(served.created_at) }}
                                    </span> <br/>
                                     <keep-alive>
                                        <sequence-status :item="served"></sequence-status>
                                    </keep-alive>
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
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="text-uppercase font-weight-light">
                        <th  class="table-primary" scope="col">
                            <small>
                                <strong>
                                    Last driver tapped:
                                </strong>
                            </small>
                        </th>
                        </tr>
                    </thead>
                    </table>
                </div>
            </div>


            <table class="table table-bordered table-striped">
                <tbody class="border border-warning">
                    <tr v-if="lastDriver.length != 0">
                        <td>
                            <div class="row">
                                <div class="col-3">
                                    <img :src="avatar_link + lastDriver.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="middle">
                                </div>
                                <div class="col-9">
                                    <p class="p-0 m-0">
                                        {{ lastDriver.driver_name }}
                                    </p>
                                    <p class="p-0 m-0" v-if="lastDriver.plate_number">
                                        {{ lastDriver.plate_number }}
                                    </p>
                                    <p class="p-0 m-0 text-danger" v-else>
                                        NO TRUCK
                                    </p>
                                    <p class="p-0 m-0" v-if="lastDriver.hauler_name">
                                        {{ lastDriver.hauler_name }}
                                    </p>
                                    <p class="p-0 m-0 text-danger" v-else>
                                        NO HAULER
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="small text-uppercase text-muted">
                                QUEUE TIME:
                            </span>
                            <p class="p-0 m-0">
                                {{ moment(lastDriver.LocalTime) }}
                            </p>
                            <keep-alive>
                                <sequence-status :item="lastDriver"></sequence-status>
                            </keep-alive>
                        </td>
                    </tr>

                    <tr :class="checkAlertMessage.tableStyle" v-if="lastDriver.length != 0">
                        <td class="text-center pb-3 pt-3" colspan="2">
                            <span class="text-small text-uppercase text-dark font-italic">
                                {{ checkAlertMessage.alertMessage }}
                            </span>
                        </td>
                    </tr>

                    <tr v-if="lastDriver.length == 0">
                        <td class="text-center" style="padding-top: 30px; padding-bottom: 30px;" colspan="2">
                            <span class="display-4 text-muted">
                                Nothing Here
                            </span>
                        </td>
                    </tr>

                </tbody>
                </table>

            </div>
        </div>



    </div>
</template>
<script>
    import moment from 'moment';
    import SequenceStatus from '../components/services/SequenceStatus.vue';

    export default {
        props: ['driverqueue'],

        components: {
            SequenceStatus
        },

        data() {
            return {
                avatar_link: '/driver_rfid/public/storage/',
                queues: [],
                todayServed: [],
                lastDriver: [],
                driverStatus: {
                    alertMessage: '',
                    tableStyle: '',
                }
            }
        },

        created() {
            this.pushToQueue()
            this.getQueues()
            this.getTodayServed()
            this.getLastDriver()
        },

        methods: {

            // trigger only after every 5 mins
            getQueues() {
                axios.get('/driver_rfid/public/getQueueEntries/' + this.driverqueue)
                .then(response => this.queues = response.data.data)
                // setTimeout(this.storeEntries, 10000); // 10 seconds
            },

            pushToQueue() {
                Echo.channel('queue.'+ this.driverqueue)
                .listen('QueueEntryEvent', (e) => {
                    this.queues.push(e.queueEntry);
                    console.log(e.queueEntry);
                });
            },

            // trigger after every 12 seconds
            getTodayServed() {
                axios.get('/driver_rfid/public/servedToday/' + this.driverqueue)
                .then(response => {
                    this.todayServed = response.data.data;
                })
                setTimeout(this.getTodayServed, 12000); // 12 seconds
            },

            // triggered after every 2 seconds
            getLastDriver() {
                axios.post('/driver_rfid/public/storeQueueEntries/' + this.driverqueue)
                .then(response => {
                    // console.log('check last driver data: ', response.data.data)
                    this.lastDriver = response.data.data
                })
                .catch((error) => {
                    console.log(error);
                });
                setTimeout(this.getLastDriver, 2000); // 2 seconds
            },

            moment(date) {
                return moment(date).format('MMM D, Y h:m:s A');
            },
        },

        computed: {
            checkAlertMessage() {
                var lastDriver = this.lastDriver;
                var driverStatus = this.driverStatus;

                if(!lastDriver.driver_availability) {
                    driverStatus.alertMessage = "Driver deactivated";
                    driverStatus.tableStyle = "table-danger";
                }
                else if (!lastDriver.truck_availability) {
                    driverStatus.alertMessage = "Truck deactivated";
                    driverStatus.tableStyle = "table-danger";
                }
                else if (lastDriver.isDRCompleted == "0000-00-00") {
                    driverStatus.alertMessage = "Please submit all outstanding DR first, then tap again!";
                    driverStatus.tableStyle = "table-danger";
                }
                else if (!lastDriver.isTappedGateFirst) {
                    driverStatus.alertMessage = "Tap first from main gate RFID";
                    driverStatus.tableStyle = "table-danger";
                }
                else if (lastDriver.shipment_number) {
                    driverStatus.alertMessage = "Shipment already assigned";
                    driverStatus.tableStyle ="table-primary";
                }
                else {
                    driverStatus.alertMessage = "Added to queue successfully!";
                    driverStatus.tableStyle ="table-success";
                }
                return driverStatus;
            }
        }

    }
</script>
