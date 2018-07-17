<template>
    <div>

    <div class="row text-muted text-left mb-3 mt-3">
        <div class="col-5">
            <small class="ml-2 text-uppercase">
                Driver Details
            </small>
        </div>
        <div class="col-2">
            <small class="text-uppercase">
                Truck Capacity
            </small>
        </div>
        <div class="col-2">
            <small class="text-uppercase">
                Last Submitted DR
            </small>
        </div>
        <div class="col-2">
            <small class="text-uppercase">
                Queue time
            </small>
        </div>
        <div class="col">
            <small class="text-uppercase">
                Status
            </small>
        </div>
    </div>

    <div id="accordion">
        <div class="card rounded-0 border-top-0 border-left-0 border-right-0" v-for="(queue, i) in filteredQueues" :key="i" v-if="!loading">
            <div class="p-3" :id="'heading-' + queue.LogID" data-toggle="collapse" :data-target="'#collapse-' + queue.LogID" aria-expanded="true" :aria-controls="'#collapse-' + queue.LogID">
               <div class="row">
                   <div class="col-5 text-left">
                        <div class="row">
                            <div class="col-1 text-center pt-3 pr-0">
                                <span style="font-size: 1.7em; font-weight: 300" class="font-weight-light">
                                    {{ queue.queue_number }}
                                </span>
                            </div>
                            <div class="col-2">
                                <img :src="avatar_link + queue.avatar" class="rounded-circle mx-auto align-middle" style="float-left; height: 70px; width: auto;"  align="middle">   
                            </div>
                            <div class="col ml-2">
                                <strong>{{ queue.driver_name }}</strong> <br/>
                                <span v-if="queue.plate_number">
                                    {{ queue.plate_number }} <br/>
                                </span>
                                <span v-else class="text-danger">
                                    NO TRUCK <br/>
                                </span>
                                 <span v-if="queue.hauler_name">
                                    {{ queue.hauler_name }}
                                </span>
                                <span class="text-danger" v-else>
                                    NO HAULER
                                </span>  
                            </div>
                        </div>
                   </div>
                    <div class="col-2">
                        <span v-if="queue.truck.capacity">
                        {{ queue.truck.capacity.description }} 
                        </span>
                        <span v-else class="text-muted">
                            N/A
                        </span>
                    </div>
                    <div class="col-2">
                        {{ queue.isDRCompleted }}
                    </div>
                   <div class="col-2">
                        {{ moment(queue.LocalTime) }}
                   </div>
                   <div class="col text-center">
                       <span class="text-center" v-if="queue.shipment">
                                <i class="fa fa-circle text-danger"></i>
                            </span>
                            <span v-else>
                                <i class="fa fa-circle text-success"></i>
                            </span>
                   </div>
               </div>
            </div>

            <div :id="'collapse-' + queue.LogID" class="collapse"  :aria-labelledby="'heading-' + queue.LogID" data-parent="#accordion">
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-5">
                        <span class="text-muted mb-3 text-uppercase d-block font-weight-bold">Truck Location(s):</span>
                                <div class="row">
                            <div class="col" v-for="(i, index) in Math.ceil(queue.truck.plants.length / 4)" :key="index">
                                <span v-for="(x,y) in queue.truck.plants.slice((i - 1) * 4, i *4)" :key="y">
                                    <span class="badge badge-secondary m-1">
                                        {{ x.plant_name }}
                                    </span><br/>
                                </span>
                            </div>
                        </div> 
                    </div>
                    <div class="col-4">
                        <span class="text-muted mb-3 text-uppercase d-block font-weight-bold">SHIPMENT INFO:</span>
                        <div class="row" v-if="queue.shipment">
                            <div class="col">
                            <small class="font-weight-light text-uppercase d-block text-muted">Shipment Number:</small>
                            <span class="d-block  mb-2">{{ queue.shipment.shipment_number }}</span>
                            
                            <small class="font-weight-light text-uppercase d-block  text-muted">Shipped Date:</small>
                            <span class="d-block  mb-2">{{ moment(queue.shipment.change_date) }}</span>
                            </div>
                            <div class="col">
                            <small class="font-weight-light text-uppercase d-block   text-muted">Company:</small>
                            <span class="d-block mb-2">{{ queue.shipment.company_server }}</span>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col">
                                <span>NO SHIPMENT YET</span>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
            </div>
        </div>

  
        <div class="row"  v-if="filteredQueues.length == 0 && !loading">
            <div class="col text-center pt-5 pb-5">
                <i class="display-4 text-muted">
                    Nothing Found
                </i>
            </div>
        </div>
            

        <div class="row" v-if="loading">
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
           

    </div>

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


    </div>
</template>
<script>

import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';

export default {

    props: ['location','search','filter'],

    components: {
        VueContentPlaceholders,
    },
    
    data() {
        return {
            loading: false,
            clicked: false,
            queues: [],
            currentPage: 0,
            itemsPerPage: 10,
            avatar_link: '/driver_rfid/public/storage/',
        }
    },

    created() {
        this.getEntries()
    },

    watch: {
        queues() {
            this.$emit('all-queue',this.queues)
        }
    },

    methods: {

        viewMore() {
            this.clicked = !this.clicked
        },

        getEntries() {
            this.loading = true
            axios.get('/driver_rfid/public/getQueueFromCreatedDate/' + this.location)
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

        queueFilter() {
            let bySearch = this.filteredEntries;

            if(this.filter == 'all') {
                return this.queues && bySearch;
            } else if(this.filter == 'with-shipment') {
                return this.queues.filter(queue => {
                    return queue.shipment != null && 
                        queue.driver_name.toLowerCase().includes(this.search.trim().toLowerCase());
                });
            } else if(this.filter == 'no-shipment') {
                return this.queues.filter(queue => {
                    return queue.shipment == null &&
                            queue.driver_name.toLowerCase().includes(this.search.trim().toLowerCase());
                });
            }

            return this.queues && bySearch;
        },

        totalPages() {
            return Math.ceil(this.queueFilter.length / this.itemsPerPage)
        },
        
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.queueFilter.slice(index, index + this.itemsPerPage);

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
<style  lang="scss" scoped>
    button {
        cursor: pointer;
    }

    .card {
        cursor: pointer;
        &:hover {
            background-color: #f2f2f2;
        }
    }

    .active-card {
        background-color: #f2f2f2;
    }

    .queue-details {
        display: flex;
        align-items: center;
    }

    // CSS Transitions
    .fade-enter-active, .fade-leave-active {
        transition: opacity .2s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
