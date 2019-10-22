<template>
  <div>
        <table class="table table-bordered" :class="{'table-striped' : !loading}">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  Driver Details  </small> </th>
            <th scope="col"> <small>  Capacity </small> </th>
            <th scope="col"> <small>  Recorded Time / Date </small> </th>
            <th scope="col"> <small>  Status</small> </th>
            </tr>
        </thead> 
        <tbody>

            <tr v-for="(entry,i) in filteredQueues" v-if="!loading" :key="i">

                <td>
                    <div class="row">
                        <div class="col-3">
                            <img :src="avatar_link + entry.avatar" class="rounded-circle mx-auto align-middle" style="height: 100px; width: auto;"  align="middle">
                        </div>
                        <div class="col-9">
                            {{ entry.driver }} <br/>
                            {{ entry.plate_number }} <br/>
                            <span v-if="entry.hauler_name == 'NO HAULER'" class="text-danger">
                                    {{ entry.hauler_name }}
                            </span>
                            <span v-else>
                                    {{ entry.hauler_name }}
                            </span>
                        </div>
                    </div>
                   
                </td>
                <td>
                    <span v-if="entry.capacity">
                        {{ entry.capacity }} 
                    </span>
                    <span class="text-muted" v-if="!entry.capacity">
                        N/A
                    </span>
                </td>
                <td>
                    <small class="text-uppercase text-muted">
                        TAPPED IN GATE
                    </small><br/>
                     {{ moment(entry.inLocalTime.date)}} 
                </td>
                <td>
                    <span v-if="entry.availability == 1 && entry.plate_availability == 1">
                        <button class="btn btn-outline-success btn-sm disabled">
                            ACTIVE
                        </button>
                    </span>
                    <span v-else>
                        <button class="btn btn-outline-danger btn-sm disabled">
                            DEACTIVATED
                        </button>
                    </span>
                </td>

            </tr>
            <tr v-if="filteredQueues.length == 0 && !loading">
                <td colspan="4">
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
                    <td colspan="4">
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

        <div class="row mt-3">
            <div class="col-6">
                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
            </div>
            <div class="col-6 text-right">
                <span>{{ entries.length }} Queue(s)</span>
            </div>
        </div>

       

    </div><!-- end template -->

</template>
<script>

    import moment from 'moment';
    import VueContentPlaceholders from 'vue-content-placeholders';
    import _ from 'lodash';

    export default {

        props: ['gate_id','searchString','date'],

        components: {
            VueContentPlaceholders,
        },

        data() {
            return {
                entries: [],
                loading: false,
                currentPage: 0,
                itemsPerPage: 5,
                avatar_link: '/storage/',
            }
        },
 
        created() {
          this.getEntries();
        },

        methods: {
            getEntries() {
                this.loading = true
                axios.get('/searchGateEntries/' + this.gate_id + '?search_date=' + this.date)
                .then(response => {
                    this.entries = response.data
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
                return _.filter(vm.entries, function(item){
                    return ~item.driver.toLowerCase().indexOf(vm.searchString.trim().toLowerCase());
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
<style scoped>
    button {
        cursor: pointer;
    }
</style>
