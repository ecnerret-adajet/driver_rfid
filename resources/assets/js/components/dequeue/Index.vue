<template>

  <div>

        <div class="content-header">
        <div class="row mb-2">
          <div class="col-6">
            <h1 class="m-0 text-dark">Dequeues</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
    </div>

    <div class="card mx-auto mb-3">
        <div class="card-header">
               All Dequeues

        </div>
        <div class="card-body">

            <div class="form-row mb-2 mt-2">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Search Plate Number</label>
                        <input type="text" class="form-control"  v-model="searchString" @keyup="resetStartRow" placeholder="Search" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Sort by status</label>
                        <select class="form-control" v-model="filter">
                            <option selected value="all">All</option>
                            <option value="pendng">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="disapproved">Disapproved</option>
                        </select>
                    </div>
                </div>

            </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="(item, index) in filterDequeues" :key="index" class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-1">

                                            <img :src="`/storage/${item.queue_entry.avatar}`" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">

                                        </div>
                                        <div class="col-sm-4">

                                            <span v-if="item.queue_entry.truck">
                                               {{ item.queue_entry.plate_number }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO TRUCK
                                            </span>

                                            <br/>
                                               {{ item.queue_entry.hauler_name }}

                                        </div>
                                        <div class="col-sm-4">
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
                                                <a href="javascript:void(0);" class="dropdown-item">Approve</a>
                                                <a href="javascript:void(0);" class="dropdown-item">Disapprove</a>
                                            </span>

                                            </div><!-- end dropdown -->
                                        </span>

                                        </div>
                                    </div>

                                </li>
                                <li v-if="filterDequeues.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO ITEM FOUND</span>
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
                    <span>{{ dequeues.length }} Dequeues</span>
                </div>
            </div>

          

        </div><!-- end card-body -->
    </div> <!-- end card -->



  </div>
</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders';
export default {

    props: {
        role: String
    },

    components: {
        VueContentPlaceholders,
    },

    data() {
        return {
            filter: 'all',
            searchString: '',
            loading: false,
            dequeues: [],
            currentPage: 0,
            itemsPerPage: 5,
        }
    },

    mounted() {
        this.getDequeues()
    },

    methods: {

        getDequeues() {
            this.loading = true
            axios.get('/api/dequeues')
            .then(response => {
                console.log('check return: ', response.data.data)
                this.dequeues = response.data.data
                this.loading = false
            })
        },

        dateFormat(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
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
        },

        searchEntries() {
            return this.dequeue.filter(item => {
                return item.queue_entry.driver_name.toLowerCase().includes(this.searchString.trim().toLowerCase()) ||
                        item.queue_entry.plate_number.toLowerCase().includes(this.searchString.trim().toLowerCase());
            });
        },
    },

    computed: {
        filteredEntries() {

            let bySearch = this.searchEntries;

            switch(this.filter) {
                case "all":
                    return this.dequeues;
                    break;
                case "pending":
                    return this.dequeues.filter(dequeue => dequeue.isApproved === 0 && dequeue.confired_by === null)
                            .filter(response => {
                                return  response.plate_number.toLowerCase().includes(this.searchString.trim().toLowerCase());
                            });
                    break;
                case "approved":
                    return this.dequeues.filter(dequeue => dequeue.isApproved === 1 && dequeue.confired_by != null)
                            .filter(response => {
                                return  response.plate_number.toLowerCase().includes(this.searchString.trim().toLowerCase());
                            });
                    break;
                case "disapproved":
                    return this.dequeues.filter(dequeue => dequeue.isApproved === 0 && dequeue.confired_by != null)
                            .filter(response => {
                                return  response.plate_number.toLowerCase().includes(this.searchString.trim().toLowerCase());
                            });
                    break;
                default:
                    return this.dequeues
                    break;
            }
            
        },
        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },
        filterDequeues() {

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
