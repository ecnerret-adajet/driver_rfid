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

            <tr v-for="queue in filteredQueues" v-if="!loading">

                <td class="text-center">
                    <span class="display-4">
                     {{ queue.log_id }}
                    </span> 
                </td>
                <td>
                    <div class="row">
                        <div class="col-3 text-center">
                            <img :src="avatar_link + queue.driver_avatar" class="rounded-circle mx-auto align-middle" style="height: 100px; width: auto;"  align="middle">
                        </div>
                         <div class="col-9">
                            {{ queue.driver_name }} <br/>
                            {{ queue.plate_number }} <br/>
                            <span v-if="queue.hauler == 'NO HAULER'" class="text-danger">
                                    {{ queue.hauler }}
                            </span>
                            <span v-else>
                                    {{ queue.hauler }}
                            </span><br/>

                        </div>
                    </div>
                   
                </td>
                 <td width="7%">
                    <span v-if="queue.capacity">
                        {{ queue.capacity }} 
                    </span>
                    <span class="text-muted" v-if="!queue.capacity">
                        N/A
                    </span>
                </td>
                   <td>
                         <div class="row">
                        <div class="col" v-for="(i, index) in Math.ceil(queue.plant_truck.length / 4)" :key="index">
                            <span v-for="(x,y) in queue.plant_truck.slice((i - 1) * 4, i *4)" :key="y">
                                <span class="badge badge-secondary m-1">
                                    {{ x }}
                                </span><br/>
                            </span>
                        </div>
                    </div>
                </td>
                <td>
                    <small class="text-uppercase text-muted">
                        LAST DR SUBMISSION
                    </small> <br/>
                    <span v-if="queue.dr_status" v-for="(status, index) in queue.dr_status">
                        <span v-if="index == 0">
                            {{ status.submission_date }}
                        </span>                                            
                    </span> <br/>
                    <small class="text-uppercase text-muted">
                        TAPPED IN QUEUE
                    </small><br/>
                     {{ moment(queue.log_time.date) }}
                </td>
                <td>
                    <span v-if="!queue.on_serving">
                        <a class="btn btn-success" href="javascript:void(0);" data-toggle="modal" :data-target="'#servingModal-'+ queue.driver_id">
                            OPEN FOR SHIPMENT
                        </a>
                    </span>
                    <span v-else>
                        <button class="btn btn-outline-danger btn-sm disabled">
                        SHIPMENT ASSIGNED
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

        <div v-for="queue in filteredQueues">

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
        


    </div>
</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';

    export default {
        props: ['search'],

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
                csrf: '',
            }
        },

        mounted() {
            this.csrf = window.Laravel.csrfToken;
        },

        created() {
            this.getQueues()
        },

        methods: {
            getQueues() {
                this.loading = true
                axios.get('/driver_rfid/public/monitor/btnDeliveries')
                .then(response => {
                    this.queues = response.data
                    this.loading = false
                })
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
                    return ~item.driver_name.toLowerCase().indexOf(vm.search.trim().toLowerCase());
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