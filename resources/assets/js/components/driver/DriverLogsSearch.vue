<template>
<div>


     <table class="table table-bordered" :class="{'table-striped' : !loading}">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  Log # </small> </th>
            <th scope="col"> <small>  CardholderID </small> </th>
            <th scope="col"> <small>  ControllerID </small> </th>
            <th scope="col"> <small>  DoorID</small> </th>
            <th scope="col"> <small>  Direction </small> </th>
            <th scope="col"> <small>  LocalTime</small> </th>
            </tr>
        </thead> 
        <tbody>

            <tr v-for="(log, i) in filteredQueues" :key="i" v-if="!loading">

                <td>{{ log.LogID }}</td>
                <td>{{ log.CardholderID }}</td>
                <td>{{ log.ControllerID }}</td>
                <td>{{ log.DoorID }}</td>
                <td>
                    <span v-if="log.Direction = 1">
                        IN
                    </span>
                    <span v-else>
                        OUT
                    </span>
                </td>
                <td>{{ moment(log.LocalTime) }}</td>

            </tr>
            <tr v-if="filteredQueues.length == 0 && !loading">
                <td colspan="8">
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
                    <td colspan="6">
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
                <span>{{ logs.length }} Queue(s)</span>
            </div>
        </div>

       

    </div><!-- end template -->

</template>
<script>

import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';

export default {
    data() {
        return {
            loading: false,
            logs: [],
            currentPage: 0,
            itemsPerPage: 5,
        }
    },

    props: ['driver','search','date'],

    component: {
        VueContentPlaceholders
    },

    created() {
        this.getLogs()
    },
    
    methods: {
        getLogs() {
            this.loading = true
            axios.get('/searchDriverLogs/' + this.driver + '/' + this.date)
            .then(response => {
                this.logs = response.data
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
            return _.filter(vm.logs, function(item){
                return ~item.LogID.toLowerCase().indexOf(vm.search.trim().toLowerCase()) || 
                        ~item.LocalTime.toLowerCase().indexOf(vm.search.trim().toLowerCase());
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
