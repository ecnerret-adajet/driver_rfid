<template>

  <div>

        <div class="content-header">
        <div class="row mb-2">
          <div class="col-6">
            <h1 class="m-0 text-dark">Replacements</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
    </div>

    <div class="card mx-auto mb-3">
        <div class="card-header">
               All Replacements

                <!-- <a class="btn btn-primary btn-sm pull-right" href="#">
                    Back
                </a> -->

                 <a class="dropdown pull-right btn btn-sm btn-outline-secondary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">

                <span>
                    <a href="javascript:void(0);" @click.prevent="openReportModal()" class="dropdown-item">Export Summary Report</a>
                </span>

                </div><!-- end dropdown -->

                <button type="button" class="btn btn-primary btn-sm pull-right mr-2" @click="openCreateModal()">
                    New Replacement
                </button>

        </div>
        <div class="card-body">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#pending" role="tab">Pending Replacements</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#approved" role="tab">Approved Replacements</a>
                    </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="pending" role="tabpanel">

            <div class="form-row mb-2 mt-2">

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
                                <li v-for="(item, index) in filterReplacements" :key="index" class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-1">

                                        <span v-if="item.driver.image">
                                            <img :src="avatar_link + item.driver.image.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </span>
                                         <span v-else>
                                            <img :src="avatar_link + item.driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </span>

                                        </div>
                                        <div class="col-sm-4">
                                            <span  style="text-transform: upppercase">{{item.driver.name}}</span> : <small v-if="item.driver.cardholder">{{ item.driver.cardholder.Name }}</small>
                                            <br/>

                                            <span v-if="item.truck">
                                               {{ item.truck.plate_number }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO TRUCK
                                            </span>

                                            <br/>
                                            <span v-if="item.hauler">
                                               {{ item.hauler.name }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO HAULER
                                            </span>

                                        </div>
                                        <div class="col-sm-4">
                                            <span class="badge badge-primary" v-if="!item.card">
                                                Card Assigned
                                            </span>
                                            <br/>
                                            <small class="text-muted">CREATED AT:</small><br/>
                                            <span>{{ dateFormat(item.created_at) }}</span>
                                        </div>
                                        <div class="col-sm-3 pull-right right">

                                         <span v-if="role == 'Monitoring' || role == 'Administrator'">
                                            <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">

                                            <span>
                                                <a href="javascript:void(0);" class="dropdown-item" @click.prevent="openApproveModal(item)">Approve Entry</a>
                                                <!-- <div class="dropdown-divider"></div> -->
                                            </span>

                                            </div><!-- end dropdown -->
                                        </span>

                                        </div>
                                    </div>

                                </li>
                                <li v-if="filterReplacements.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO DRIVER FOUND</span>
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
                    <span>{{ replacements.length }} For Replacements</span>
                </div>
            </div>


            </div>

                <div class="tab-pane pt-3" id="approved" role="tabpanel">
                    <approved :data="approveData"></approved>
                </div>

            </div>



        </div><!-- end card-body -->
    </div> <!-- end card -->

    <create :showModal="showModal"
            @returnShowModal="showModal = $event"
            @storeResponse="storeResponse">
    </create>

    <approve :showModal="showModalApprove"
            :approveData="approveData"
            @returnshowModal="showModalApprove = $event"
            @response="approveResponse">
    </approve>

    <report :showModal="showModalReport"
            @returnshowModal="showModalReport = $event">
    </report>

  </div>
</template>
<script>
import moment from 'moment';
import Create from '../replacements/Create.vue';
import Approve from '../replacements/Approve.vue';
import Report from '../replacements/Report.vue';
import ArrovedReplacements from '../replacements/ApprovedReplacements.vue';
import VueContentPlaceholders from 'vue-content-placeholders';
export default {

    props: {
        role: String
    },

    components: {
        VueContentPlaceholders,
        Create,
        Approve,
        approved: ArrovedReplacements,
        Report
    },

    data() {
        return {
            searchString: '',
            showModal: false,
            showModalApprove: false,
            showModalReport: false,
            approveData: {},
            loading: false,
            replacements: [],
            currentPage: 0,
            itemsPerPage: 5,
            driver_link: '/driver_rfid/public/drivers/',
            avatar_link: '/driver_rfid/public/storage/',
        }
    },

    mounted() {
        this.getReplacements()
    },

    methods: {
        getReplacements() {
            this.loading = true
            axios.get('/driver_rfid/public/api-replacements')
            .then(response => {
                this.replacements = response.data.data
                this.loading = false
            })
        },
        dateFormat(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        },
        storeResponse(event) {
            return this.replacements.unshift(event)
        },
        openReportModal() {
            this.showModalReport = true;
        },
        openCreateModal() {
            this.showModal = true;
        },
        openApproveModal(object) {
            this.showModalApprove = !this.showModalApprove
            if(this.showModalApprove) {
                this.approveData = object
            }
        },
        approveResponse(event) {
            let findIndex = this.replacements.findIndex(item => item.id === event.id);
             return Promise.resolve(findIndex)
             .then(result => {
                  this.replacements.splice(result, 1);
                  this.resetRow()
             })
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
    },

    computed: {
        filteredEntries() {
            return this.replacements.filter(item => {
                return item.driver.name.toLowerCase().includes(this.searchString.trim().toLowerCase());
            })
        },
        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },
        filterReplacements() {

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
