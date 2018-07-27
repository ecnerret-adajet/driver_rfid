<template>
    <div>

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="row my-2">
                <div class="col">
                    <h1 class="m-0 text-dark">Entries Report</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>

        <div class="row my-2">
            <div class="col">
                <button class="btn btn-primary float-right">Export to xslx</button>
                    <div class="form-group mt-3">
                    <div class="input-group input-append w-25">
                        <span class="input-group-addon add-on"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control w-50" v-model="search" placeholder="Search Driver Name">
                    </div>
                    </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header py-3 bg-white border-bottom-0">
                <select class="form-control float-right w-25" v-model="selectedLocation">
                    <option v-for="(location, l) in locations" :key="l" :value="location.id">{{ location.title }}</option>
                </select>
                <div class="input-group input-append date w-25" id="datePicker">
                    <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                    <input type="date" class="form-control" v-model="date" />
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
                                    {{ parseDate(entry.driverpass) }}
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
                                </div>
                                <div class="col-2">
                                     <span class="d-block text-uppercase text-muted small">Plant In</span>
                                    <p>{{ parseDate(entry.truck_plant_in) || 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">SAP Loading-start</span>
                                    <p>{{ entry.sap_ts_in || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">SAP Loading-end</span>
                                    <p>{{ entry.sap_ts_out || 'N/A' }} </p>
                                </div>
                                 <div class="col-3">
                                     <span class="d-block text-uppercase text-muted small">Truckscale In</span>
                                    <p>{{ entry.ts_time_in || 'N/A' }}</p>
                                    <span class="d-block text-uppercase text-muted small">Truckscale Out</span>
                                    <p>{{ parseDate(entry.ts_time_out) || 'N/A' }} </p>
                                     <span class="d-block text-uppercase text-muted small">Plant Out</span>
                                    <p>{{ parseDate(entry.gate_time_out) || 'N/A' }} </p>
                                </div>
                                <div class="col-2">
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
                                     <span class="d-block text-uppercase text-muted small">Driver Pass to Queue</span>
                                    <!-- <p>{{ entry.ts_time_in || 'N/A' }}</p> -->
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

    export default {

        data() {
            return {
                search: '',
                loading: false,
                entries: [],
                locations: [],
                selectedLocation: 1,
                lastDr: '',
                date: moment(new Date()).format('YYYY-MM-DD'),
                currentPage: 0,
                itemsPerPage: 10,
            }
        },

        created() {
            this.getEntries()
            this.getLocations()
        },

        methods: {

            getEntries() {
                this.loading = true
                axios.get('/driver_rfid/public/displayEntries/' + this.selectedLocation + '/' + this.date)
                .then(response => {
                    this.entries = response.data.data
                    this.loading = false
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

