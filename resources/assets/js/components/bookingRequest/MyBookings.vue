<template>

 <div>
            <div class="form-row mb-2 mt-4">

                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" class="form-control"  v-model="searchString" @keyup="resetStartRow" placeholder="Search" />
                    </div>
                </div>

            </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="(bookRequest, i) in filterBookingRequest" :key="i" class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-1">

                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                            </span>

                                        </div>
                                         <div class="col-sm-2">

                                            <small class="text-muted text-uppercase ">
                                               Reference Number
                                            </small> <br/>
                                            <span>
                                                STO : {{ bookRequest.order_reference_no }} 
                                            </span> <br/> <br/>
                                           

                                            <small class="text-muted text-uppercase ">
                                               Booking Date
                                            </small> <br/>
                                            <span>
                                               {{ bookRequest.booking_date }}
                                            </span> 
                                            <br>
                                        </div>
                                        <div class="col-sm-3">

                                            <small class="text-muted text-uppercase ">
                                                Driver Name
                                            </small> <br/>
                                            <span v-if="bookRequest.driver_name">
                                               {{ bookRequest.driver_name }}
                                            </span> 
                                            <span v-else class="text-muted">
                                               N/A
                                            </span> 
                                            <br/> <br/>

                                            <small class="text-muted text-uppercase ">
                                                Plate Number
                                            </small> <br/>
                                            <span v-if="bookRequest.plate_number">
                                              {{ bookRequest.plate_number }}
                                            </span> 
                                             <span v-else class="text-muted">
                                               N/A
                                            </span> 
                                            <br/>

                                        </div>

                                        <div class="col-sm-3 pull-right right">
                                            <small class="text-uppercase text-muted">Mode of Shipment </small><br/>
                                            <span>
                                                {{ bookRequest.mode_of_shipment }}
                                            </span><br/> <br/>
                                             <small class="text-uppercase text-muted">Ship Type </small><br/>
                                            <span>
                                                {{ bookRequest.ship_type }}
                                            </span><br/> <br/>
                                        </div>

                                         <div class="col-sm-3">
                                            
                                            <small class="text-muted text-uppercase ">
                                                Status
                                            </small> <br/>

                                            <span v-if="bookRequest.shippers_name">
                                                Updated <i class="fa fa-circle" style="color:green" aria-hidden="true"></i>
                                            </span> 
                                            <span v-else>
                                               Pending <i class="fa fa-circle" style="color:red" aria-hidden="true"></i>
                                            </span> 
                                            
                                            <br/> <br/>

                                            <small class="text-muted text-uppercase ">
                                                Shipper's Name
                                            </small> <br/>
                                            <span v-if="bookRequest.shippers_name">
                                                {{ bookRequest.shippers_name }}
                                            </span> 
                                             <span v-else class="text-muted">
                                               N/A
                                            </span> 
                                            <br/>

                                        </div>


                                    </div>
                                </li>
                                <li v-if="filterBookingRequest.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO RECORD FOUND</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                         <div class="row center-align" style="display: flex; align-items: center; justify-content: center;" v-if="loading">
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
                                </content-placeholders>
                             </div>
                        </div>
                    </div>
                </div>


                 <div  class="row mt-3">
                <div class="col-6">
                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span> {{ bookings.length }} Booked Requests</span>
                </div>
            </div>


  </div>

</template>
<script>
import VueContentPlaceholders from 'vue-content-placeholders';
import moment from 'moment';

    export default {
         props: ['user'],

        components: {
            VueContentPlaceholders,
        },

         data() {
             return {
                searchString: '',
                loading: false,
                bookings: [],
                arrivals: [],
                csrf: '',
                currentPage: 0,
                itemsPerPage: 5,
             }
         },

         created() {

             this.fetchBookedEntries()

         },

         methods: {

             fetchBookedEntries() {
                 axios.get('/api/my-bookings')
                 .then(response => {
                     this.bookings = response.data.data
                 })
             },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m A');
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
            return this.bookings.filter(item => {
                return item.order_reference_no.includes(this.searchString.trim().toLowerCase());
            })
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filterBookingRequest() {

            var index = this.currentPage * this.itemsPerPage;
            var arrivals_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1){
                this.currentPage = 0;
            }

            return arrivals_array;
        }
    }

    }
</script>
