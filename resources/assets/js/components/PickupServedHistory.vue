<template>
    <div>
            <div class="form-row mt-3 mb-2">

                <!-- <div class="col-2">
                    <div class="form-group">
                        <select name="age" class="form-control">
                            <option selected value="1">Filter Pickups</option>
                            <option value="2">Created by me</option>
                            <option value="3">All</option>
                        </select>
                    </div>
                </div> -->

                <div class="col-12">
                    <div class="form-group">
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search Driver Name" />
                    </div>
                </div>

            </div> <!-- end row -->

             <div class="table-responsive">

            <table class="table table-bordered" :class="{ 'table-striped' : !loading }">
                <thead>
                    <tr class="text-uppercase font-weight-light">
                    <th scope="col"> <small>  Cardholder </small> </th>
                    <!-- <th scope="col"> <small>  DO Status </small> </th> -->
                    <th scope="col"> <small>  Driver Details </small> </th>
                    <th scope="col"> <small>  DO Details </small> </th>
                    <th scope="col"> <small>  Activity Details </small> </th>
                    <th scope="col"> <small>  Created By </small> </th>
                    <th></th>
                    </tr>
                </thead>
            <tbody>

                <tr v-for="pickup in filteredPickups" v-if="!loading">
                    <td>
                        <small class="btn btn-outline-success btn-sm align-middle" v-if="pickup.cardholder">
                            {{ pickup.cardholder.Name }}
                            <br>
                        </small>
                    </td>
                    <!-- <td>
                        <small>
                            <div v-if="pickup.pickup_status === 'served'" class="btn btn-outline-success btn-sm  text-uppercase align-middle">
                                SERVED
                            </div>
                            <div v-else class="btn btn-outline-danger btn-sm  text-uppercase align-middle">
                                NOT YET SERVED
                            </div>

                        </small>
                    </td> -->
                    <td>
                        {{ pickup.driver_name }} <br/>
                        {{ pickup.plate_number }} <br/>
                        {{ pickup.company }}
                    </td>
                    <td>
                        <!-- {{ pickup.do_number }}
                        <br> -->
                        <div v-for="(do_number, a) in pickup.do_numbers" :key="a">

                            <p v-if="do_number.status =='C'" style="color:green">{{ do_number.do_number }}  {{ do_number.status ?  " - " + "Served" : ""}}</p>
                            <p v-else style="color:black">{{ do_number.do_number }}  {{ do_number.status ?  " - " + "Not yet served" : ""}}</p>
                        </div>
                    </td>
                    <td>
                       <div class="row">

                            <div class="col">
                                <small class="text-uppercase text-muted">Date Arrived</small> <br/>
                                <span v-if="pickup.activation_date">
                                    {{ moment(pickup.activation_date) }}  <br/>
                                </span>
                                <span v-if="!pickup.activation_date">
                                    NOT YET ARRIVED <br/>
                                </span>

                                <small class="text-uppercase text-muted">Checkout Date</small>  <br/>
                                <span v-if="pickup.deactivated_date">
                                    {{ moment(pickup.deactivated_date) }}
                                </span>
                                <span v-if="!pickup.cardholder && !pickup.deactivated_date">
                                    N/A
                                </span>
                                <span v-if="pickup.cardholder && !pickup.deactivated_date">
                                   STILL IN PLANT
                                </span>
                            </div>

                            <div class="col">
                                <small class="text-uppercase text-muted">Time Rendered</small> <br/>
                                <span v-if="pickup.deactivated_date">
                                    {{ dateDiff(pickup.activation_date, pickup.deactivated_date) }} Hour(s)
                                </span>
                                <span v-else class="text-muted">
                                    N/A
                                </span>
                            </div>
                        </div>

                    </td>
                    <td>
                      {{ pickup.user.name }} <br/><br/>
                      <div class="row">
                          <div class="col-lg-6">
                               <small class="text-uppercase text-muted">Date Created</small> <br/>
                                 {{ moment(pickup.created_at) }}
                          </div>
                          <div class="col-lg-6">
                               <small class="text-uppercase text-muted">Pickup Date</small> <br/>
                                 {{ pickDateFormat(pickup.pickup_date) }}
                          </div>
                      </div>

                    </td>
                    <td>
                        <span v-if="pickup.pickup_status != 'served'">
                            <a class="dropdown pull-right btn btn-outline-primary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">
                                    <a :href="'unserved/' + pickup.id + '/edit'" class="dropdown-item">Update</a>
                                    <a href="#" class="dropdown-item text-danger" data-toggle="modal" :data-target="'#pickupCancel-'+ pickup.id">Cancel Pickup</a>
                            </div><!-- end dropdown -->
                        </span>
                    </td>
                </tr>
                <tr v-if="filteredPickups.length == 0 && !loading">
                    <td colspan="8">
                        <div class="row">
                            <div class="col text-center pt-3 pb-3">
                                <span class="display-4 text-muted">
                                    No Pickup Found
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

            </div>

            <div  class="row mt-3">
                <div class="col-6">
                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span>{{ pickups.length }} Pickups</span>
                </div>
            </div>


            <div v-for="pickup in filteredPickups">
            <!-- Deactivate Modal -->
            <div class="modal fade" :id="'pickupCancel-' + pickup.id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="driverModalLabel">Cancel RFID</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>


                </div>
                <div class="modal-body text-center">


                    <em>Are you sure you want to proceed with this action?</em>


                </div>
                <div class="modal-footer">
                    <form  method="post" :action="'/pickups/unserved/'+pickup.id">

                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="_method" value="delete">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>

                </div>
            </div>
            </div><!-- end modal -->
            </div>

    </div>
</template>
<script>
    import moment from 'moment';
    import VueContentPlaceholders from 'vue-content-placeholders';
    import _ from 'lodash';

    export default {

        components: {
            VueContentPlaceholders,
        },

        data() {
            return {
                searchString: '',
                loading: false,
                pickups: [],
                currentPage: 0,
                itemsPerPage: 5,
                csrf: '',
            }
        },

        mounted() {
            this.csrf = window.Laravel.csrfToken;
        },


        created() {
            this.getPickup()
        },

        methods: {
            getPickup() {
                this.loading = true
                axios.get('/driver_rfid/public/getPickupHistory')
                .then(response => {
                    this.pickups = response.data
                    this.loading = false
                });
            },

            dateDiff(startTime, endTime) {
                var a = moment(startTime);
                var b = moment(endTime);
                return b.diff(a, 'hours');
            },

             moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },

            pickDateFormat(date) {
                if(date) {
                    return moment(date).format('MMMM D, Y h:m:s A');
                }
                return 'NO PICK DATE'
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
                return _.filter(vm.pickups, function (item) {
                    return ~item.driver_name.toLowerCase().indexOf(vm.searchString.trim().toLowerCase());
                });
            },

            totalPages() {
                return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
            },

            filteredPickups() {

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
