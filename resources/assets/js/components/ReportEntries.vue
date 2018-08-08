<template>
    <div>

        <!-- Content Header (Page header) -->
        <div class="content-header mb-4">
            <div class="row my-2">
                <div class="col">
                    <h1 class="m-0 text-dark">Entries Report</h1>
                </div><!-- /.col -->
                <div class="col">
                    <report-entries-data v-if="!loadingReport" :location="selectedLocation" :date="start_date" :entries="reportEntries"></report-entries-data>
                </div>
            </div><!-- /.row -->
        </div>

        <div class="card mb-4">
            <div class="card-header py-3 bg-white border-bottom-0">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="text-muted text-uppercase small">Search</label>
                            <input type="text" class="form-control" v-model="search" placeholder="Search Driver Name">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="text-muted text-uppercase small">Date</label>
                            <input type="date" class="form-control" v-model="start_date"  :max="maxDate"/>
                        </div>
                    </div>
                    <!-- <div class="col">
                        <div class="form-group">
                        <label  class="text-muted text-uppercase small">End Date</label>
                        <input type="date" class="form-control" v-model="end_date" :max="maxDate" :min="minDate" />
                        </div>
                    </div> -->
                    <div class="col">
                        <div class="form-group">
                        <label class="text-muted text-uppercase small">Location</label>
                         <select class="form-control" v-model="selectedLocation">
                            <option v-for="(location, l) in locations" :key="l" :value="location.id">{{ location.title }}</option>
                        </select>
                        </div>
                    </div>
                </div>




            </div>
            <div class="card-body p-0">

                <div class="mx-3">
                    <div class="row border border-right-0 border-left-0 p-3 bg-light text-uppercase text-muted" style="font-size: 0.9em; letter-spacing: 0.1em;">
                        <div class="col-3"><strong>Name</strong></div>
                        <div class="col-2"><strong>Plate Number</strong></div>
                        <div class="col-3"><strong>Hauler Name</strong></div>
                        <div class="col-2"><strong>Driver Pass</strong></div>
                        <div class="col-2"><strong>Last Submitted DR</strong></div>
                    </div>
                </div>

                <div class="accordion mb-3 rounded-0" id="accordionEntries" v-if="!loading">
                    <div v-for="(entry, e) in filteredResult" :key="e" class="card rounded-0 border-0">
                        <div class="card-header bg-white rounded-0 border-left-0 border-right-0 entry-pointer" :id="`heading${entry.id}`" data-toggle="collapse" :data-target="`#collapse-${entry.id}`" aria-expanded="true" aria-controls="collapseOne">
                            <div class="row">
                                <div class="col-3">
                                    <img :src="`/driver_rfid/public/storage/${entry.avatar}`" class="rounded-circle mx-auto align-middle px-3" style="float-left; height: 45px; width: auto;"  align="middle">
                                    {{ entry.driver }}
                                </div>
                                <div class="col-2">
                                    {{ entry.plate }}
                                </div>
                                <div class="col-3">
                                    {{ entry.hauler }}
                                </div>
                                <div class="col-2">
                                    {{ entry.capacity || 'N/A' }}
                                </div>
                                <div class="col-2">
                                    {{ entry.last_dr_date }}
                                </div>
                            </div>
                        </div>
                        <div :id="`collapse-${entry.id}`" class="collapse show" :aria-labelledby="`heading${entry.id}`" data-parent="#accordionEntries">
                        <div class="card-body bg-light">
                            <div class="row mx-2">
                                <div class="col-3">
                                    <span class="d-block text-uppercase text-muted small">Queue Time</span>
                                    <p>{{ parseDate(entry.queue_time) || 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Shipment Date</span>
                                    <p>{{ parseDate(entry.shipment) || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">company</span>
                                    <p>{{ entry.company || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">Plant In</span>
                                    <p>{{ parseDate(entry.truck_plant_in) || 'N/A' }}</p>
                                </div>
                                 <div class="col-3">
                                     <span class="d-block text-uppercase text-muted small">Driver Pass</span>
                                    <p>{{ parseDate(entry.driverpass) || 'N/A' }}</p>
                                     <span class="d-block text-uppercase text-muted small">Truckscale In</span>
                                    <p>{{ parseDate(entry.ts_time_in) || 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Truckscale Out</span>
                                    <p>{{ parseDate(entry.ts_time_out) || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">Plant Out</span>
                                    <p>{{ parseDate(entry.gate_time_out) || 'N/A' }} </p>
                                </div>
                                <div class="col-2">
                                    <span class="d-block text-uppercase text-muted small">SAP Loading-start</span>
                                    <p>{{ parseDate(entry.sap_loading_start) || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">SAP Loading-end</span>
                                    <p>{{ parseDate(entry.sap_loading_end) || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">SAP Truckscale In</span>
                                    <p>{{ parseDate(entry.sap_ts_in) || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">SAP Truckscale Out</span>
                                    <p>{{ parseDate(entry.sap_ts_out) || 'N/A' }} </p>
                                </div>                                <div class="col-2">
                                    <span class="d-block text-uppercase text-muted small">Driver Pass to Queue</span>
                                    <p>{{ entry.driverpass && entry.queue_time ? timeDiff(entry.driverpass,entry.queue_time) : 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Queue to Shipment</span>
                                    <p>{{ entry.queue_time && entry.shipment ? timeDiff(entry.queue_time,entry.shipment) : 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Shipment to Plant In</span>
                                    <p>{{ entry.shipment && entry.truck_plant_in ? timeDiff(entry.shipment,entry.truck_plant_in) : 'N/A' }}</p>
                                     <span class="d-block text-uppercase text-muted small">Plant In to Truckscale In</span>
                                    <p>{{ entry.truck_plant_in && entry.ts_time_in ? timeDiff(entry.truck_plant_in,entry.ts_time_in) : 'N/A' }}</p>
                                </div>
                                <div class="col-2">
                                    <span class="d-block text-uppercase text-muted small">Truckscale In to Loading Start</span>
                                    <p>{{ entry.ts_time_in && entry.sap_loading_start ? timeDiff(entry.ts_time_in,entry.sap_loading_start) : 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Loading Start to Loading End</span>
                                    <p>{{ entry.sap_loading_start && entry.sap_loading_end ? timeDiff(entry.sap_loading_start,entry.sap_loading_end) : 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Loading End to Truckscale Out</span>
                                    <p>{{ entry.sap_loading_end && entry.ts_time_out ? timeDiff(entry.sap_loading_end,entry.ts_time_out) : 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Truckscale Out to Plant out</span>
                                    <p>{{ entry.ts_time_out && entry.gate_time_out ? timeDiff(entry.ts_time_out,entry.gate_time_out) : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

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

            </div> <!-- end body card -->
        </div> <!-- end card -->
    </div>
</template>
<script>

    import VueContentPlaceholders from 'vue-content-placeholders';
    import moment from 'moment';
    import Toasted from 'vue-toasted';
    import ReportEntriesData from './ReportEntriesData.vue';

    Vue.use(Toasted)

    export default {

        components: {
            ReportEntriesData
        },

        data() {
            return {
                entries: [],
                reportEntries: [],
                loadingReport: false,
                search: '',
                loading: false,
                locations: [],
                selectedLocation: 1,
                lastDr: '',
                start_date: moment(new Date()).format('YYYY-MM-DD'),
                end_date: moment(new Date()).format('YYYY-MM-DD'),
                currentPage: 0,
                itemsPerPage: 10,
            }
        },

        created() {
            this.getEntries()
            this.getLocations()
            this.getReportEntries()
        },

        watch: {
            start_date() {
                this.end_date = this.start_date
                this.resetStartRow()
                this.getEntries()
                this.getReportEntries()
            },
            // end_date() {
            //     this.resetStartRow()
            //     this.getEntries()
            // },
            selectedLocation() {
                this.resetStartRow()
                this.getEntries()
                this.getReportEntries()
            }
        },

        methods: {

            successMessage() {
                Vue.toasted.show("Load Successfully!", {
                    theme: "primary",
                    position: "bottom-right",
                    duration : 5000
                });
            },

            getEntries() {
                this.loading = true
                axios.get('/driver_rfid/public/displayEntries/' + this.selectedLocation + '/' + this.start_date + '/' + this.end_date)
                .then(response => {
                    this.entries = response.data.data
                    this.loading = false
                    this.successMessage()
                })
            },

            getReportEntries() {
                this.loadingReport = true
                axios.get('/driver_rfid/public/displayEntriesReport/' + this.selectedLocation + '/' + this.start_date)
                .then(response => {
                    this.reportEntries = response.data
                    this.loadingReport = false
                })
            },

            getLocations() {
                axios.get('/driver_rfid/public/driverqueues')
                .then(response => this.locations = response.data)
            },

            lastDrSubmitted(plate_number) {
                axios.get('/driver_rfid/public/api/last_dr_date/' + plate_number)
                .then(resonse => {
                    this.lastDr = response.data
                    console.log(response.data)
                })
            },

            parseDate(date) {
                if(date) {
                    return moment(date).format('MMMM D, Y h:m:s A');
                } else {
                    return null;
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
            },

            timeDiff(driver, queue) {
                var a = moment(driver);
                var b = moment(queue);
                return moment.utc(b.diff(a)).format("HH:mm:ss");
            }

        },

        computed: {

            maxDate() {
                return moment(new Date()).format('YYYY-MM-DD');
            },

            minDate() {
                return  moment().startOf('month').format('YYYY-MM-DD');
            },

            filteredEntries() {
                return this.entries.filter(item => item.driver.toLowerCase().includes(this.search.trim().toLowerCase()));
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
<style lang="scss" scoped>
    .entry-pointer {
        cursor: pointer;
        &:hover {
            background-color: #f2f2f2 ! important;
        }
    }
</style>

