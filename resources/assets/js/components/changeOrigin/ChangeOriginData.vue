<template>
<div>
                <div class="form-row mb-2 mt-4">

                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" class="form-control"  v-model="search" @keyup="resetStartRow" placeholder="Search" />
                    </div>
                </div>

            </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="(origin, i) in filterChangeOrigins" :key="i" class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-1">

                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                            </span>

                                        </div>
                                        <div class="col-sm-5">
                                           <a href="#">
                                               <span v-if="origin.truck">
                                                    {{ origin.truck.plate_number }}
                                                </span>
                                                <span v-else>
                                                    NO PLATE NUMBER
                                                </span>
                                          </a> : <br/>

                                            <span v-if="origin.hauler" class="text-muted">
                                               {{ origin.hauler.name }}
                                            </span>
                                            <span v-else class="text-muted">
                                                NO HAULER
                                            </span>

                                            <br/>

                                            <span>
                                                 {{origin.driver_name}}
                                            </span>

                                        </div>
                                        <div class="col-sm-2">

                                            <span class="text-success" v-if="origin.truck.availability == 1">
                                             <i class="fa fa-circle" style="color:green" aria-hidden="true"></i>  Active
                                            </span>
                                            <span class="text-danger" v-if="origin.truck.availability == 0">
                                            <i class="fa fa-circle" style="color:red" aria-hidden="true"></i>   Deactivated
                                            </span>

                                        </div>
                                        <div class="col-sm-2">

                                            <span class="text-success" v-if="origin.is_approved == 1">
                                             <i class="fa fa-circle" style="color:green" aria-hidden="true"></i>  Approved
                                            </span>
                                            <span class="text-danger" v-else>
                                             <i class="fa fa-circle" style="color:red" aria-hidden="true"></i>  Pending
                                            </span>

                                        </div>
                                        <div class="col-sm-2">

                                                <span v-if="origin.approvalType === 0">
                                                      <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">

                                                        <span>
                                                            <a href="javascript:void(0);" class="dropdown-item" data-toggle="modal" @click="approvalModal(origin)">Approval</a>
                                                        </span>

                                                        </div>
                                                </span>

                                                <span v-else>
                                                    <button disabled v-if="origin.approvalType.id === 1" class="btn btn-outline-success pull-right">{{ origin.approvalType.name }}</button>
                                                    <button disabled v-if="origin.approvalType.id === 2" class="btn btn-outline-danger pull-right">{{ origin.approvalType.name }}</button>
                                                </span>



                                        </div>

                                    </div>
                                </li>
                                <li v-if="filterChangeOrigins.length == 0"  class="list-group-item">
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
                    <span>{{ changeOrigins.length }} Entries </span>
                </div>
            </div>


        <approval
            :showModal="showModal"
            :changeOrigin="toPassObj"
            @callbackShowModal="showModal = $event"
            @callbackUpdate="toPassObj = $event"></approval>

  </div>

</template>

<script>

import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
import ChangeOriginApprovalModal from './ChangeOriginApprovalModal';
import _ from 'lodash';
Vue.use(Toasted)


export default {

    props: {
        url: String
    },

    components: {
        VueContentPlaceholders,
        approval:ChangeOriginApprovalModal
    },

    data() {
        return {
            showModal: false,
            toPassObj: {},
            search: '',
            date: moment(new Date()).format('YYYY-MM-DD'),
            changeOrigins: [],
            loading: false,
            currentPage: 0,
            itemsPerPage: 5,
            showModal: false,
        }
    },

    watch: {
        showModal() {
            console.log('check show modal: ', this.showModal)
        }
    },


    created() {
        this.loadChangeOrigins()
    },

    computed: {
        filteredEntries() {
            const vm = this;
            return _.filter(vm.changeOrigins, function (item) {
                return ~item.driver_name.toLowerCase().indexOf(vm.search.trim().toLowerCase());
            });
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filterChangeOrigins() {

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

        approvalModal(changeOrigin) {
            this.showModal = true;
            if(this.showModal === true) {
                this.toPassObj = changeOrigin
            }
        },

        loadChangeOrigins() {
            this.loading = true;
            axios.get(`${this.url}`)
            .then(response => {
                console.log('check data: ', response.data)
                this.changeOrigins = response.data.data
                this.loading = false
            })
        },

        generatePickup() {
            this.loadChangeOrigins()
        },

        resetModal(event) {
            if(event === false) {
                return this.showModal = false
            }
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


