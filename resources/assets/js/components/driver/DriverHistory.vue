<template>
    <div>

    <div class="form-row mb-2 mt-3">
                     
                <div class="col-12">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Search</label>
                        <input type="text" class="form-control"  v-model="search" placeholder="Search by shipment number or company" />
                    </div>
                </div>
                
        </div> <!-- end row -->


     <table class="table table-bordered" :class="{'table-striped' : !loading}">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  CarholderID # </small> </th>
            <th scope="col"> <small>  CardID # </small> </th>
            <th scope="col"> <small>  Plate # </small> </th>
            <th scope="col"> <small>  Vendor </small> </th>
            <th scope="col"> <small>  Date  </small> </th>
            </tr>
        </thead> 
        <tbody>

            <tr v-for="(history, h) in filteredQueues" :key="h" v-if="!loading">

                <td>{{ history.cardholder_id }}</td>
                <td>{{ history.card_no }}</td>
                <td>{{ history.plate_number }}</td>
                <td>{{ history.vendor }}</td>
                <td>{{ moment(history.created_at) }}</td>
    

            </tr>
            <tr v-if="filteredQueues.length == 0 && !loading">
                <td colspan="5">
                    <div class="row">
                        <div class="col text-center pt-3 pb-3">
                            <span class="display-4 text-muted">
                                Nothing Found
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-if="loading">
                    <td colspan="5">
                        <div class="row">
                            <div class="col">
                                <content-placeholders style="border: 0 ! important;" :rounded="true">
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

        <div class="row mt-3">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ histories.length }} Queue(s)</span>
            </div>
        </div>

    </div>
</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';

export default {

    components: {
        VueContentPlaceholders
    },

    props: ['driver'],

    data() {
        return {
            loading: false,
            histories: [],
            currentPage: 0,
            itemsPerPage: 5,
            search: ''
        }
    },

    created() {
        this.getHistories()
    },

    methods: {
        getHistories() {
            this.loading = true
            axios.get('/driver_rfid/public/versionObject/' + this.driver)
            .then(response => {
                this.histories = response.data
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
            return _.filter(vm.histories, function(item){
                return ~item.plate_number.toLowerCase().indexOf(vm.search.trim().toLowerCase()) || 
                        ~item.vendor.toLowerCase().indexOf(vm.search.trim().toLowerCase());
            });
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },
        
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

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
