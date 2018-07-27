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
        </div>

        <table class="table table-hover">
            <thead class="text-uppercase text-muted" style="font-size: 0.9em; letter-spacing: 0.1em;">
                <th>Driver Detail</th>
                <th>Plate Number</th>
                <th>Hauler</th>
                <th>Date/Time</th>
                <th>Status</th>
            </thead>
            <tbody>
                <tr v-for="(entry, e) in filteredResult" :key="e" v-if="!loading">
                    <td>
                        <img :src="`/driver_rfid/public/storage/${entry.avatar}`" class="rounded-circle mx-auto align-middle px-3" style="float-left; height: 45px; width: auto;"  align="middle">
                        {{ entry.driver_name }}
                    </td>
                    <td>
                        {{ entry.plate_number }}
                    </td>
                    <td>
                        {{ entry.hauler_name }}
                    </td>
                    <td>
                        {{ parseDate(entry.LocalTime) }}
                    </td>
                    <td>
                      <span class="text-center" v-if="entry.truck_availability == 1 && entry.driver_availability == 1">
                            <i class="fa fa-circle text-success"></i>
                        </span>
                        <span v-else>
                            <i class="fa fa-circle text-danger"></i>
                        </span>
                    </td>
                </tr>
                <tr v-if="filteredResult.length == 0 && !loading">
                    <td colspan="5">
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
            search: '',
            date: moment(new Date()).format('YYYY-MM-DD'),
            entries: [],
            currentPage: 0,
            itemsPerPage: 10,
        }
    },

    watch: {
        location() {
           return this.getGates();
        },
        date() {
            return this.getGates();
        }
    },

    created() {
        this.getGates()
    },

    methods:  {
        getGates() {
            this.loading = true
            axios.get(`/driver_rfid/public/getGateEntries/${this.location}/${this.date}`)
            .then(response => {
                this.entries = response.data
                this.loading = false
            })
        },

        parseDate(date) {
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
        },
    },

    computed: {
        filteredEntries() {
            return this.entries.filter(item => item.driver_name.toLowerCase().includes(this.search.trim().toLowerCase()));
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filteredResult() {

            var index = this.currentPage * this.itemsPerPage;
            var drivers_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

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
