<template>
    <div>

        <div class="row mx-3">
            <div class="col">
                <div class="form-group">
                    <label class="control-label text-muted">Search</label>
                    <input class="form-control" type="text" v-model="search" placeholder="Search Driver Name">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="control-label text-muted">Date</label>
                    <input type="date" class="form-control" v-model="date">
                </div>
            </div>
            <div class="col-3">
                    <label class="control-label text-muted d-block">&nbsp;</label>
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <button @click="filter='all'" :class="{ active : filter == 'all' }" type="button" class="btn  btn-outline-secondary">All</button>
                        <button @click="withShipment()" :class="{ active : filter == 'with-shipment' }" type="button" class="btn  btn-outline-secondary">With Shipment</button>
                        <button @click="noShipment()" :class="{ active : filter == 'no-shipment' }" type="button" class="btn  btn-outline-secondary">No Shipment</button>
                    </div>
            </div>
        </div>

        <table class="table table-hover">
            <thead class="text-uppercase text-muted" style="font-size: 0.9em; letter-spacing: 0.1em;">
                <th>Queue #</th>
                <th>Driver Details</th>
                <th>Plate Number</th>
                <th>Hauler</th>
                <th>Truck Locations</th>
                <!-- <th>Last Submitted DR</th> -->
                <th>Dates</th>
                <th>Status</th>
            </thead>
            <tbody>
                <tr v-for="(entry, e) in filteredResult" :key="e" v-if="!loading">
                    <td class="text-center">
                        {{ entry.queue_number }}
                    </td>
                    <td>
                        <img :src="`/driver_rfid/public/storage/${entry.avatar}`" class="rounded-circle mx-auto align-middle px-3" style="float-left; height: 45px; width: auto;"  align="middle">
                        {{ entry.driver_name }}
                    </td>
                    <td>
                        {{ entry.plate_number }} <br/>
                        <span class="text-muted">
                        {{ entry.truck.capacity.description || 'N/A' }}
                        </span>
                    </td>
                    <td>
                        {{ entry.hauler_name }}
                    </td>
                    <td>
                        <div class="row">
                        <div class="col" v-for="(i, index) in Math.ceil(entry.truck.plants.length / 4)" :key="index">
                            <span v-for="(x,y) in entry.truck.plants.slice((i - 1) * 4, i *4)" :key="y">
                                <span class="badge badge-secondary m-1">
                                    {{ x.plant_name }}
                                </span><br/>
                            </span>
                        </div>
                    </div>
                    </td>
                    <td>
                        <span class="text-uppercase text-muted" style="font-size: 0.8em;">Last Submitted DR</span><br/>
                        {{ entry.isDRCompleted }}
                        <br/>
                        <br/>
                        <span class="text-uppercase text-muted" style="font-size: 0.8em;">Queue Date/Time</span><br/>
                        {{ parseDate(entry.LocalTime) }}
                    </td>
                    <td>
                      <span class="text-center" v-if="entry.shipment">
                            <button class="btn btn-outline-danger btn-sm disabled">
                                SHIPMENT ASSIGNED
                            </button>
                            <br/>
                            {{ entry.shipment.shipment_number }} <br/>
                            {{ parseDate(entry.shipment.change_date) }}
                        </span>
                        <span v-else>
                            <button class="btn btn-outline-success btn-sm disabled">
                                OPEN FOR SHIPMENT
                            </button>
                        </span>
                    </td>
                </tr>
                <tr v-if="filteredResult.length == 0 && !loading">
                    <td colspan="7">
                        <div class="row">
                            <div class="col text-center pt-5 pb-5">
                                <i class="display-4 text-muted">
                                    Nothing Found
                                </i>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row m-3" v-if="loading">
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

        <div class="row m-3">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ entries.length }} Queue(s)</span>
            </div>
        </div>

    </div>
</template>
<script>

import VueContentPlaceholders from 'vue-content-placeholders';
import moment from 'moment';

export default {

    props: ['location'],

    data() {
        return {
            loading: false,
            filter: 'all',
            search: '',
            date: moment(new Date()).format('YYYY-MM-DD'),
            entries: [],
            currentPage: 0,
            itemsPerPage: 10,
        }
    },

    watch: {
        location() {
           return this.getQueues();
        },
        date() {
            return this.getQueues();
        }
    },

    created() {
        this.getQueues()
    },

    methods:  {
        getQueues() {
            this.loading = true
            axios.get(`/driver_rfid/public/getQueueEntriesJson/${this.location}/${this.date}`)
            .then(response => {
                this.entries = response.data.data
                this.filter = 'all'
                this.loading = false
            })
        },

        withShipment() {
            this.filter ='with-shipment';
            this.resetStartRow();
        },

        noShipment() {
            this.filter ='no-shipment';
            this.resetStartRow();
        },

        parseDate(date) {
                return moment(date).format('YYYY-MM-D h:m:s A');
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
        },
    },

    computed: {

        filteredEntries() {
            return this.entries.filter(item => item.driver_name.toLowerCase().includes(this.search.trim().toLowerCase()));
        },

        categoryFilter() {
            let bySearch = this.filteredEntries;

            if(this.filter == 'all') {
                return this.entries && bySearch;
            } else if(this.filter == 'with-shipment') {
                return this.entries.filter(entry => {
                    return entry.shipment != null &&
                     entry.driver_name.toLowerCase().includes(this.search.trim().toLowerCase());
                });
            } else if(this.filter == 'no-shipment') {
                return this.entries.filter(entry => {
                    return entry.shipment == null &&
                     entry.driver_name.toLowerCase().includes(this.search.trim().toLowerCase());
                });
            }
        },

        totalPages() {
            return Math.ceil(this.categoryFilter.length / this.itemsPerPage)
        },

        filteredResult() {

            var index = this.currentPage * this.itemsPerPage;
            var drivers_array = this.categoryFilter.slice(index, index + this.itemsPerPage);

            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1){
                this.currentPage = 0;
            }

            return drivers_array;

        }
    }
}
</script>
