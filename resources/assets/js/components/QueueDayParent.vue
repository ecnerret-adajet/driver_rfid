<template>
  <div>

      
            
        <div class="row mt-4 mb-2">

        
            <div class="col">

                <div class="row mb-3">
                    <div class="col-4">
                        <input type="text" placeholder="Search from entries" class="form-control" v-model="searchString">
                    </div>
                    <!-- <div class="col">
                        <select class="ml-auto w-25 form-control">
                            <option  selected>Bank Transfer</option>
                            <option  selected>Manager's Check</option>
                            <option  selected>Payroll Application</option>
                        </select>
                    </div> -->
                </div>
            
                <app-queue-day-entry @all-queue="queues = $event"
                                     v-if="!selected"
                                    :location="driverqueue"
                                    :filter="filter" 
                                    :search="searchString">
                </app-queue-day-entry>

                 <app-queue-older-entry @all-queue="queues = $event"
                                     v-if="selected"
                                    :location="driverqueue"
                                    :filter="filter" 
                                    :search="searchString">
                </app-queue-older-entry>


            
            
            </div>

             <div class="col-2">

                <div class="list-group rounded-0">
                    <a href="javascript:void(0);" @click="goToOlderEntries()" :class="{ active : !selected }" class="list-group-item list-group-item-action rounded-0">Within 24 hours</a>
                    <a href="javascript:void(0);" @click="backToWithinDay()" :class="{ active : selected }" class="list-group-item list-group-item-action rounded-0">Older than 24 hours</a>
                </div>

                <div v-show="!selected" class="list-group mt-3 rounded-0">
                    <a href="javascript:void(0);" @click="filter='all'" :class="{ active : filter == 'all' }" class="list-group-item list-group-item-action rounded-0">All</a>
                    <a href="javascript:void(0);" @click="filter='with-shipment'" :class="{ active : filter == 'with-shipment' }" class="list-group-item list-group-item-action rounded-0">With Shipment</a>
                    <a href="javascript:void(0);" @click="filter='no-shipment'" :class="{ active : filter == 'no-shipment' }" class="list-group-item list-group-item-action rounded-0">Open Shipment</a>
                </div>

                <ul v-show="!selected" class="list-group list-group-flush mt-3">
                    <li class="list-group-item border-top-0" :class="{ 'font-weight-bold' : filter == 'all', 'text-muted' : filter != 'all' }">{{ queues.length }} Total in Queue</li>
                    <li class="list-group-item" :class="{ 'font-weight-bold' : filter == 'with-shipment', 'text-muted' : filter != 'with-shipment' }">{{ withShipment }} Assigned Shipment</li>
                    <li class="list-group-item" :class="{ 'font-weight-bold' : filter == 'no-shipment' , 'text-muted' : filter != 'no-shipment' }">{{ noShipment }} Open Shipment</li>
                </ul>

                <ul v-show="selected" class="list-group list-group-flush mt-3">
                    <li class="list-group-item border-top-0" :class="{ 'font-weight-bold' : filter == 'all', 'text-muted' : filter != 'all' }">{{ queues.length }} Total in Queue</li>
                </ul>

            </div>

           

        </div>


    
       
    </div><!-- end template -->

</template>
<script>
    import moment from 'moment';
    import QueueDayEntry from './QueueDayEntry.vue';
    import QueueOlderEntry from './QueueOlderEntry.vue';

    export default {

        props: ['driverqueue'],

        components: {
            appQueueDayEntry : QueueDayEntry,
            appQueueOlderEntry : QueueOlderEntry,
        },

        data() {
            return {
                selected: false,
                isSearching: false,
                searchString: '',
                queues: [],
                filter: 'all',
                loadingLastAssigned: false,
                loadingCount: false,
                date: '',
            }
        },

        methods: {

            backToWithinDay() {
                this.selected = true
                this.filter = 'all'
            },

            goToOlderEntries() {
                this.selected = false
                this.filter = 'all'
            },

            backToLatest() {
                this.selected = false;
                this.date = null;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            }
        },

        computed: {

            // Returns all queue with shipment
            withShipment() {
                return this.queues.filter(queue => queue.shipment != null).length;
            },

            noShipment() {
                return this.queues.filter(queue => queue.shipment == null).length;
            }


        }

    }

</script>
