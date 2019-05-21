<template>

<div>
     <div class="row mt-4">
            <div class="col-4">
                <div class="form-group">
                    <label class="text-uppercase text-muted">Search</label>
                    <input class="form-control" placeholder="Search Entries" type="text" v-model="search">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="text-uppercase text-muted">Search Date</label>
                    <input class="form-control" type="date" v-model="date">
                </div>
            </div>
            <div class="col-4">
                <label>&nbsp;</label>
                <button class="btn btn-block btn-primary" :disabled="loading" @click="generatePickup()">Generate</button>
            </div>
        </div>



        <div class="row">
        <div class="col">
            <table style="font-size: 70%" :class="{ 'table-striped table-hover' : !loading }" class="table ">
                <thead class="text-muted text-uppercase font-weight-light" style="font-size: 13px">
                    <tr>
                        <th>Pickup #</th>
                        <th>Driver Details</th>
                        <th style="width: 10%">DO Details</th>
                        <th>Checked Date</th>
                        <th>Truckscale Date</th>
                        <th>Between</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, i) in filteredPickups" :key="i" v-if="!loading">
                        <td>{{ item.pickup_deploy_name }}</td>
                        <td>
                        <span>{{ item.driver_name }}</span> <br/>
                        <span>{{ item.plate_number }}</span> <br/>
                        <span>{{ item.company }}</span> <br/>
                        </td>
                        <td style="width: 10%">
                            <span>{{ item.do_number }}</span>
                        </td>
                        <td>
                            <span>{{ item.created_at }}</span>
                        </td>
                        <td>
                            <small class="text-uppercase text-muted">
                                Truckscale In:
                            </small>
                            <br/>
                            <span>{{ item.truckscale_in }}</span>
                            <br/>
                            <small class="text-uppercase text-muted">
                                Truckscale Out:
                            </small>
                            <br/>
                            <span>{{ item.truckscale_out }}</span>
                            <br/>
                        </td>
                        <td>
                            <span>{{ item.time_diff }}</span>
                        </td>
                        <td>
                            <button v-show="item.deactivated_date === 'no-deactivate-date' &&
                                     item.activation_date === 'no-activation-date'"
                                     @click.prevent="assignCard(item)" type="button" class="btn btn-outline-primary btn-sm">
                                     ASSIGN CARD
                            </button>

                            <button v-show="item.activation_date != 'no-activation-date' &&
                                     item.deactivated_date === 'no-deactivate-date'"
                                     @click.prevent="deativateCard(item)" type="button" class="btn btn-outline-danger btn-sm">
                                     DEACTIVATE CARD
                            </button>

                            <button v-show="item.activation_date != 'no-activation-date' &&
                                          item.deactivated_date != 'no-deactivate-date'"
                                          type="button" class="btn btn-outline-success btn-sm disabled">
                                          SERVED
                            </button>

                        </td>
                    </tr>
                    <tr v-if="filteredPickups.length == 0 && !loading">
                        <td colspan="7">
                            <div class="row">
                                <div class="col text-center pt-5 pb-5">
                                    <img  src="/driver_rfid/public/images/archive.png" class="mx-auto mt-2" style="height: 150px; width: auto;"  align="middle">
                                    <br/>
                                    <span class="display-4">
                                        Nothing Found
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="loading">
                        <td colspan="7">
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
    </div>

    <div class="row mt-3">
        <div class="col-6">
            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
            <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
        </div>
        <div class="col-6 text-right">
            <span>{{ pickups.length }} Entries</span>
        </div>
    </div>


    <deactivate  :message="'Are you sure you want to proceed?'"
            :title="'Deactivate Pickup'"
            :data="toPassObj"
            :path-url="'/driver_rfid/public/api/pickups-deactivate'"
            :showModal="showModal"
            @response="childResponse($event)"
            @returnShowModal="resetModal($event)">
    </deactivate>

    <assign-card :showModal="showAssignCard"
                 :pickup="pickup"
                 @returnShowModal="showAssignCard = $event"
                 @response="assignedCardResponse($event)">
    </assign-card>



</div>

</template>

<script>

import moment from 'moment';
import Deactivate from './Deactivate.vue';
import AssignCard from './AssignCard.vue';
import VueContentPlaceholders from 'vue-content-placeholders';
Vue.use(Toasted)


export default {

    props: {
        url: String
    },

    components: {
        VueContentPlaceholders,
        Deactivate,
        AssignCard
    },

    data() {
        return {
            showModal: false,
            showAssignCard: false,
            toPassObj: {},
            pickup: {},
            search: '',
            date: moment(new Date()).format('YYYY-MM-DD'),
            pickups: [],
            loading: false,
            currentPage: 0,
            itemsPerPage: 5,
        }
    },

    watch: {
        showModal() {
            console.log('check show modal: ', this.showModal)
        }
    },


    mounted() {
        this.loadPickupData()
    },

    computed: {
        filteredEntries() {
            let checkSearch = this.search ? this.search.trim().toLowerCase() : '';
            return this.pickups.filter(item => {
                return item.driver_name.toLowerCase().includes(checkSearch) ||
                        item.plate_number.toLowerCase().includes(checkSearch);
            })
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filteredPickups() {

            var index = this.currentPage * this.itemsPerPage;
            var pickup_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1){
                this.currentPage = 0;
            }

            return pickup_array;
        }

    },

    methods: {

        loadPickupData() {
            this.loading = true;
            axios.get(`${this.url}${this.date}`)
            .then(response => {
                this.pickups = response.data.data
                this.loading = false
            })
        },

        generatePickup() {
            this.loadPickupData()
        },

        resetModal(event) {
            if(event === false) {
                return this.showModal = false
            }
        },

        deativateCard(item) {
            console.log('deactivating card')
            this.toPassObj = item;
            return Promise.resolve(this.toPassObj)
            .then(resonse => {
                return this.showModal = true;
            })
        },

        childResponse(event) {
            if(event.status === 'patch') {
                let findIndex = this.pickups.findIndex(item => item.id === event.data.id)
                return Promise.resolve(findIndex)
                .then(response => {
                    this.pickups.splice(response, 1);
                    this.resetRow()
                })
            }
        },

        assignedCardResponse(event) {

            let findIndex = this.pickups.findIndex(item => item.id === event.id)
            return Promise.resolve(findIndex)
                .then(response => {
                    this.pickups.splice(response, 1);
                    this.resetRow()
                })

        },

        assignCard(item) {
            console.log('Assigning card')
            this.pickup = item;
            return Promise.resolve(this.pickup)
            .then(resonse => {
                return this.showAssignCard = true;
            })
        },

        searchDate(date) {
            return moment(date).format('YYYY-MM-DD');
        },

        moment(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        },

        dateformat(date) {
            return moment(date).format('MMM D, YY h:m:s A');
        },

        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        resetRow() {
            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }
    }
}
</script>
<style>
button:disabled {
  cursor: not-allowed;
  pointer-events: all !important;
}
</style>


