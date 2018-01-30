<template>

    <div class="card mx-auto mb-3">
        <div class="card-header">

           <div class="form-row mb-2 mt-2">
                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control"  v-model="searchKey" placeholder="Search" />
                    </div>
                </div>
            </div>

         
        </div>
        <div class="card-body p-0">
          
    
       

            <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group list-group-flush">
                                <li v-for="entry in paginatedUsers" class="list-group-item">
                                    <div class="row mt-2">   
                                        <div class="col-sm-1">

                                            <img :src="avatar_link + entry.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                            
                                        
                                        </div>
                                        <div class="col-sm-4">
                                            {{entry.driver_name}} 
                                            <br/>
                    
                                            <span v-if="entry.plate_number">
                                               {{ entry.plate_number }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO TRUCK
                                            </span>

                                            <br/>
                                            <span v-if="entry.hauler">
                                               {{ entry.hauler }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO HAULER
                                            </span>
                                      
                                        </div>
                                        <div class="col-sm-3">

                                            <small class="text-muted text-uppercase">PLANT IN</small><br/>
                                            <span v-if="entry.plant_in">
                                                {{ moment(entry.plant_in.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.plant_in">
                                                No plant in
                                            </span>

                                            <br/>

                                            <small class="text-muted text-uppercase">PLANT OUT</small><br/>
                                            <span v-if="entry.plant_out">
                                                {{ moment(entry.plant_out.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.plant_out">
                                                No plant out
                                            </span>

                                        </div>
                                        <div class="col-sm-3 pull-right right">
                                        
                                            <small class="text-muted text-uppercase">Truckscale IN</small><br/>
                                            <span v-if="entry.truckscale_in">
                                                {{ moment(entry.truckscale_in.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.truckscale_in">
                                                No Truckscale in
                                            </span>

                                            <br/>

                                            <small class="text-muted text-uppercase">Truckscale OUT</small><br/>
                                            <span v-if="entry.truckscale_out">
                                                {{ moment(entry.truckscale_out.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.truckscale_out">
                                                No Truckscale out
                                            </span>
                                        
                                        </div>
                                    </div>

                                </li>
                                <li v-if="paginatedUsers.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO DRIVER FOUND</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <li class="list-group-item mx-auto"  v-if="loading">
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
                        </li>


                         <!-- <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;" v-if="loading">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div> -->
                    </div>
                </div>    
        </div>

        <div class="card-footer text-muted">
                <div  class="row">
                    <div class="col">
                        <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                            <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                        <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
                    </div>
                </div>
        </div>
      </div>

           


</template>
<script>
import moment from 'moment';
import VueContentPlaceholders from 'vue-content-placeholders'
import _ from 'lodash';

// Vue.use(VueContentPlaceholders)

export default {
    data() {
        return {
            loading: false,
            driver_link: '/driver_rfid/public/drivers/',
            avatar_link: '/driver_rfid/public/storage/',
            entries: [],
            searchKey: '',
            currentPage: 0,
            itemsPerPage: 5,
            resultCount: 0
        }
    },

    components: {
        VueContentPlaceholders
    },

    created() {
        this.getEntries()
    },

    methods: {
        getEntries() {
            this.loading = true
            axios.get('/driver_rfid/public/hometest')
            .then(response => {
                this.entries = response.data
                this.loading = false
            });
        },
        
        setPage: function(pageNumber) {
          this.currentPage = pageNumber
        },

        moment(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }
    },

    computed: {
        totalPages: function() {
          return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filteredEntries: function () {
            const vm = this;
            if(!vm.searchKey) {
                return vm.entries;
            }
            return _.filter(vm.entries, function (item) {
                return ~item.driver_name.toLowerCase().indexOf(vm.searchKey.toLowerCase());
            });
        },

        paginatedUsers: function() {
            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }
            var index = this.currentPage * this.itemsPerPage
            return this.filteredEntries.slice(index, index + this.itemsPerPage)
        }
    }
}
</script>

<style scoped>
    button {
        cursor: pointer;
    }
</style>