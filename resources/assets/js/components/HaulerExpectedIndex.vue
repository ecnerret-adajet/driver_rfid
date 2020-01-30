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
                                <li v-for="(arrival, i) in filterArrivals" :key="i" class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-1">

                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                            </span>

                                        </div>
                                        <div class="col-sm-5">
                                           <a href="#">
                                               <span v-if="arrival.truck">
                                                    {{ arrival.plate_number }}
                                                </span>
                                                <span v-else>
                                                    NO PLATE NUMBER
                                                </span>
                                          </a> : <br/>

                                            <span class="text-muted">
                                               {{ arrival.hauler_name }}
                                            </span>

                                            <br/>

                                            <span v-if="arrival.truck.driver">
                                                 {{arrival.truck.driver[0].name}}
                                            </span>
                                            <span v-else>
                                                NO DRIVER ASSIGNED
                                            </span>

                                        </div>
                                        <div class="col-sm-3">

                                            <span v-if="arrival.truck.availability == 1">
                                               Active <i class="fa fa-circle" style="color:green" aria-hidden="true"></i>
                                            </span>
                                            <span v-if="arrival.truck.availability == 0">
                                               Deactivated <i class="fa fa-circle" style="color:red" aria-hidden="true"></i>
                                            </span>

                                        </div>

                                        <div class="col-sm-3 pull-right right">
                                            <span class="text-muted">Expected Time Arrival: </span><br/>
                                            <span>
                                                {{ moment(arrival.expected_arrival) }}
                                            </span>
                                        </div>


                                    </div>
                                </li>
                                <li v-if="filterArrivals.length == 0"  class="list-group-item">
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
                                    <!-- <content-placeholders-text :lines="3" /> -->
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
                    <span>{{ arrivals.length }} Expected Arrivals</span>
                </div>
            </div>


  </div>

</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';

    export default {
         props: ['user'],

        components: {
            VueContentPlaceholders,
        },

         data() {
             return {
                searchString: '',
                loading: false,
                arrivals: [],
                csrf: '',
                currentPage: 0,
                itemsPerPage: 5,
             }
         },

        mounted() {
            this.csrf = window.Laravel.csrfToken;
        },

         created(){
             this.getExpectedArrivals()
         },

         methods: {

             moment(date) {
                return moment(date).format('MMMM D, Y h:m A');
            },

             getExpectedArrivals(){
                 this.loading = true
                 axios.get('/api/haulers-arrivals/'+ this.user)
                 .then(response => {
                     this.arrivals = response.data
                     this.loading = false
                 });
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

            return _.filter(vm.arrivals, function (item) {
                return ~item.plate_number.toLowerCase().indexOf(vm.searchString.trim().toLowerCase());
            });
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filterArrivals() {

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
