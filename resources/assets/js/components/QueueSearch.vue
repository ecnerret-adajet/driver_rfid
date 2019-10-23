<template>
  <div>

     <table class="table table-bordered" :class="{'table-striped' : !loading}">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  Queue # </small> </th>
            <th scope="col"> <small>  Driver Details </small> </th>
            <th scope="col"> <small>  Capacity </small> </th>
            <th scope="col"> <small>  Truck Location(s) </small> </th>
            <th scope="col"> <small>  Recorded Time /Date </small> </th>
            <th scope="col"> <small>  Status</small> </th>
            <th scope="col"> <small>  Plant Out</small> </th>
            <th scope="col"> <small>  Option</small> </th>
            </tr>
        </thead>
        <tbody>

            <tr :class="{ 'table-danger ' : queue.dequeue }" v-for="(queue, i) in filteredQueues" :key="i" v-show="!loading">

                <td class="text-center">
                    <span class="display-4">
                     {{ queue.queue_number }}
                    </span>
                </td>
                <td>
                    <div class="row">
                        <div class="col-3">
                            <img :src="avatar_link + queue.avatar" class="rounded-circle mx-auto align-middle" style="height: 100px; width: auto;"  align="middle">
                        </div>
                        <div class="col-9">
                            {{ queue.driver_name }} <br/>
                            <span v-if="queue.plate_number">
                                {{ queue.plate_number }} <br/>
                            </span>
                            <span v-else class="text-danger">
                                NO TRUCK
                            </span>
                            <span v-if="queue.hauler_name">
                                {{ queue.hauler_name }}
                            </span>
                            <span class="text-danger" v-else>
                                NO HAULER
                            </span> <br/>
                            <span>
                               {{ queue.LogID }}
                            </span>
                        </div>
                    </div>

                </td>
                 <td width="7%">
                    <span v-if="queue.truck.capacity">
                        {{ queue.truck.capacity.description }}
                    </span>
                    <span v-else class="text-muted">
                        N/A
                    </span>
                </td>
                <td>
                    <div class="row">
                        <div class="col" v-for="(i, index) in Math.ceil(queue.truck.plants.length / 4)" :key="index">
                            <span v-for="(x,y) in queue.truck.plants.slice((i - 1) * 4, i *4)" :key="y">
                                <span class="badge badge-secondary m-1">
                                    {{ x.plant_name }}
                                </span><br/>
                            </span>
                        </div>
                    </div>
                </td>
                <td>
                    <small class="text-uppercase text-muted">
                        LAST DR SUBMISSION
                    </small> <br/>
                        {{ queue.isDRCompleted }}
                        <br/>
                    <small class="text-uppercase text-muted">
                        TAPPED IN QUEUE
                    </small><br/>
                     {{ dateformat(queue.LocalTime) }}

                </td>
                <td>
                    <span v-if="checkIfForShipment(queue) != 0">
                        <button :class="{ ' btn-outline-danger' : checkIfForShipment(queue) == 1, ' btn-outline-warning' : checkIfForShipment(queue) == 2 }" class="btn  btn-sm disabled mb-2">
                            SHIPMENT ASSIGNED
                        </button>
                        <br/>
                        <small class="d-block text-uppercase text-muted">
                            Shipment Number
                        </small>
                        <span class="d-block">
                        {{ queue.shipment.shipment_number }} <br/>
                        <!-- {{ getUnix(queue.shipment.change_date) - getUnix(queue.LocalTime) }} -->
                        </span>
                        <small class="d-block text-uppercase text-muted">
                            Shipment Date
                        </small>
                        <span class="d-block">
                        {{ dateformat(queue.shipment.change_date) }}
                        </span>
                    </span>
                    <span v-else>
                        <button class="btn btn-outline-success btn-sm disabled">
                            OPEN FOR SHIPMENT
                        </button>
                    </span>
                </td>
                <td>
                    {{  plantOutDate(queue.plant_out.date)  }}
                </td>
                <td>

                    <a v-if="checkIfForShipment(queue) === 0" :class="{ ' disabled ' : queue.dequeue  }"  class="dropdown pull-right btn btn-outline-secondary" href="#" id="entryDequeue" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="entryDequeue">
                        <a href="javascript:void(0);" @click="openCreateDequeue(queue.id)" class="dropdown-item">Remove from queue</a>
                    </div><!-- end dropdown -->
                    
                </td>

            </tr>
            <tr v-if="filteredQueues.length == 0 && !loading">
                <td colspan="8">
                    <div class="row">
                        <div class="col text-center pt-3 pb-3">
                            <span class="display-4 text-muted">
                                Nothing Found
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-if="loading">
                    <td colspan="8">
                        <div class="row">
                            <div class="col">
                                <content-placeholders style="border: 0 ! important;" :rounded="true">
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <hr/>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <hr/>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <hr/>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <!-- <content-placeholders-text :lines="3" /> -->
                                </content-placeholders>
                             </div>
                        </div>
                    </td>
                </tr>
        </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ queues.length }} Queue(s)</span>
            </div>
        </div>


    </div><!-- end template -->

</template>
<script>

    import Toasted from 'vue-toasted';
    import moment from 'moment';
    import VueContentPlaceholders from 'vue-content-placeholders';

    Vue.use(Toasted)

    export default {

        props: ['location','filter','search','dequeue'],

        components: {
            VueContentPlaceholders,
        },

        data() {
            return {
                date: moment(new Date()).format('YYYY-MM-DD'),
                queues: [],
                currentPage: 0,
                itemsPerPage: 5,
                avatar_link: '/storage/',
            }
        },

        watch: {
            dequeue() {
                if(this.dequeue) {
                    this.returnDequeue(this.dequeue)
                }
            },
            withShipment() {
                this.$emit('withShipment',this.withShipment);
            },

            noShipment() {
                this.$emit('noShipment',this.noShipment);
            },

            date() {
                return this.getEntries();
            },

            filter() {
                return this.resetStartRow();
            }
        },

        created() {
          this.getEntries();
        },

        computed: {

            withShipment() {
                return this.queues.filter(queue => queue.shipment != null).length;
            },

            noShipment() {
                return this.queues.filter(queue => queue.shipment == null).length;
            },

            searchEntries() {
                return this.queues.filter(item => {
                    return item.driver_name.toLowerCase().includes(this.search.trim().toLowerCase()) ||
                            item.plate_number.toLowerCase().includes(this.search.trim().toLowerCase());
                });
            },

            filteredEntries() {
                let bySearch = this.searchEntries;

                switch (this.filter) {
                    case "all":
                        return this.queues && bySearch;
                        break;

                    case "with-shipment":

                        return this.queues.filter(queue => {
                            return queue.shipment != null
                        })
                        .filter(response => {
                            return  response.plate_number.toLowerCase().includes(this.search.trim().toLowerCase());
                        });

                        break;

                    case "no-shipment":

                        return this.queues.filter(queue => {
                            return queue.shipment === null && queue.plant_out === ""
                        })
                        .filter(response => {
                            return  response.plate_number.toLowerCase().includes(this.search.trim().toLowerCase());
                        });

                        break;

                    case "in-transit": 
                        // code here for in-transit 
                        break;

                    default:
                        return this.queues && bySearch;
                        break;
                }
            },

            totalPages() {
                return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
            },

            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            }
        },

        methods: {

            successMessage() {
                Vue.toasted.show("Dequeued entry is subject for approval!", {
                    theme: "primary",
                    position: "bottom-right",
                    duration : 5000
                });
            },

            getUnix(dateTime) {
                return moment(dateTime).unix()
            },

            openCreateDequeue(queue) {
                this.$emit('passQueueId', queue);
            },

            returnDequeue(event) {
                console.log('dequeue return')
                this.filteredQueues.map(item => {
                    if(item.id === event.queue_entry_id) {
                        this.successMessage()
                        return item.dequeue = event.dequeue
                    }
                })

            },

            getEntries() {
                this.loading = true
                axios.get(`/searchQueueEntriesFeed/${this.location}`)
                .then(response => {
                    this.queues = response.data.data
                    this.loading = false
                });
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },

            dateformat(date) {
                return moment(date).format('MMM D, YY h:m:s A');
            },

            plantOutDate(date) {
                if(date != null) {
                    return moment(date).format('MMM D, YY h:m:s A');
                }
            },

            checkIfForShipment(queueObj) {

                // 0 === [w/o shipment][w/o plantout] = open shipment
                // 1 === [w/shipment][w/plantout] = in transit
                // 2 === [w/shipment][w/o plantout] = current in plant

                // include DR submission date
                //queue.isDRCompleted


                // if(!queueObj.shipment) {
                //     return [ 0, 'Open Shipment' ];
                // } else if  (queueObj.plant_out.date != '' && queueObj.shipment) {

                //     let queue =  moment(queueObj.LocalTime).unix();
                //     let shipment = moment(queueObj.shipment.change_date).unix();


                //     return queue > shipment ? [ 0, 'Open Shipment' ] : [ 1, 'In Transit' ];
                // } else if (queueObj.plant_out.date == '' && queueObj.shipment) {
                //     return [ 2, 'Currently in plant' ]
                // }
                // return [ 0, 'Open shipment' ]

                switch (true) {
                    case queueObj.isDRCompleted === "UNPROCESS" && queueObj.shipment != null:
                        return 2;
                        break;
                    case queueObj.shipment === null:
                        return 0;
                        break;
                    case queueObj.isDRCompleted === "UNPROCESS":
                        return 0;
                        break;
                    case queueObj.plant_out.date === "" && queueObj.shipment === null:
                        return 0;
                        break;   
                    default: 
                        return 1;
                        break;
                }


            },

            setPage(pageNumber) {
                this.currentPage = pageNumber;
            },

            resetStartRow() {
                this.currentPage = 0;
            },

            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },

            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }
        },

        


    }

</script>
<style scoped>
    button {
        cursor: pointer;
    }
</style>
