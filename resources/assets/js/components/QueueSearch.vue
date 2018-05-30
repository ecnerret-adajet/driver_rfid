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
            </tr>
        </thead> 
        <tbody>

            <tr v-for="(queue, i) in filteredQueues" :key="i" v-if="!loading">

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
                     {{ moment(queue.LocalTime) }}
                </td>
                <td>
                    <span class="text-center" v-if="queue.shipment">
                        <button class="btn btn-outline-danger btn-sm disabled">
                            SHIPMENT ASSIGNED 
                        </button>
                        <br/>
                         {{ queue.shipment.shipment_number }}
                    </span>
                    <span v-else>
                        <button class="btn btn-outline-success btn-sm disabled">
                            OPEN FOR SHIPMENT
                        </button>
                    </span>
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

    import moment from 'moment';
    import VueContentPlaceholders from 'vue-content-placeholders';
    import _ from 'lodash';

    export default {

        props: ['location','search','date'],

        components: {
            VueContentPlaceholders,
        },

        data() {
            return {
                loading: false,
                queues: [],
                currentPage: 0,
                itemsPerPage: 5,
                avatar_link: '/driver_rfid/public/storage/',
            }
        },

        created() {
          this.getEntries();
        },

        methods: {
            
            getEntries() {
                this.loading = true
                axios.get('/driver_rfid/public/searchQueueEntriesFeed/' + this.location + '?search_date=' + this.date)
                .then(response => {
                    this.queues = response.data
                    this.loading = false
                });
            },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
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

        computed: {
            filteredEntries() {
                const vm = this;
                return _.filter(vm.queues, function(item){
                    return ~item.driver_name.toLowerCase().indexOf(vm.search.trim().toLowerCase()) || 
                            ~item.plate_number.toLowerCase().indexOf(vm.search.trim().toLowerCase());
                });
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
        }


    }

</script>
<style scoped>
    button {
        cursor: pointer;
    }
</style>
