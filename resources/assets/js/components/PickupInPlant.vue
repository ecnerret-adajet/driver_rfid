<template>
    <div>

        <table class="table table-bordered table-striped">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  Cardholder </small> </th>
            <th scope="col"> <small>  Driver Details </small> </th>
            <th scope="col"> <small>  DO Details </small> </th>
            <th scope="col"> <small>  Activity Details </small> </th>
            <th scope="col"> <small>  Created By </small> </th>
            </tr>
        </thead> 
        <tbody>
                <tr v-for="pickup in filteredPickups" v-if="!loading">
                    <td>
                        <small class="btn btn-outline-success btn-sm align-middle" v-if="pickup.cardholder">
                            {{ pickup.cardholder.Name }}
                        </small>
                        <small class="btn btn-outline-danger btn-sm  text-uppercase align-middle" v-else>
                            NOT YET SERVED
                        </small>
                    </td>
                    <td>
                        {{ pickup.driver_name }} <br/>
                        {{ pickup.plate_number }} <br/>
                        {{ pickup.company }}
                    </td>
                    <td>{{ pickup.do_number }}</td>
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
                    {{ pickup.user.name }} <br/>
                   <small class="text-uppercase text-muted">Date Created</small> <br/>
                    {{ moment(pickup.created_date) }} 
                    </td>
                </tr>
                <tr v-if="filteredPickups.length == 0 && !loading">
                    <td colspan="8">
                        <row>
                            <div class="col text-center pt-3 pb-3">
                                <span class="display-4 text-muted">
                                    No Pickup Found
                                </span>
                            </div>
                        </row>
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

    </div>
</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';

    export default {

        props:['search'],

        components: {
            VueContentPlaceholders,
        },

        data() {
            return {
                loading: false,
                pickups: [],
                currentPage: 0,
                itemsPerPage: 5,
            }
        },

        created() {
            this.getPickup()
        },

        methods: {

            getPickup() {
                this.loading = true
                axios.get('/driver_rfid/public/pickupInPlant')
                .then(response => {
                    this.pickups = response.data
                    this.loading = false
                })
            },

            dateDiff(startTime, endTime) {
                var a = moment(startTime);   
                var b = moment(endTime);   
                return b.diff(a, 'hours');
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
                return _.filter(vm.pickups, function (item) {
                    return ~item.driver_name.toLowerCase().indexOf(vm.search.trim().toLowerCase());
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