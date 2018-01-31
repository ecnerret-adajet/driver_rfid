<template>

    <div class="card mx-auto mb-3 rounded-0 border-top-0">

        <div class="card-body p-0">
          
            <div class="form-row mb-2 mt-2">
                <div class="col-md-12">
                    <div class="form-group mb-0 p-3">
                        <input type="text" class="form-control"  v-model="searchKey" @keyup="resetStartRow" placeholder="Search" />
                    </div>
                </div>
            </div>
       

            <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group list-group-flush">
                                <li v-for="entry in paginateEntries" class="list-group-item">
                                    <div class="row mt-2">   
                                        <div class="col-sm-1">
                                            <img :src="avatar_link + entry.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">                                            
                                        </div>
                                        <div class="col-sm-3">
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
                                               <button class="disabled btn btn-outline-danger btn-sm text-uppercase">
                                                   NO PLANT IN
                                               </button>
                                            </span>

                                            <br/>

                                            <small class="text-muted text-uppercase">QUEUE TIME</small><br/>
                                            <span v-if="entry.on_queue">
                                                {{ moment(entry.on_queue.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.on_queue">
                                                 <button class="disabled btn btn-outline-danger btn-sm text-uppercase">
                                                   NOT IN QUEUE
                                               </button>
                                            </span>

                                        </div>
                                        <div class="col-sm-2">
                                        
                                            <small class="text-muted text-uppercase">Truckscale IN</small><br/>
                                            <span v-if="entry.truckscale_in">
                                                {{ moment(entry.truckscale_in.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.truckscale_in">
                                                <button class="disabled btn btn-outline-danger btn-sm text-uppercase">
                                                   NO TRUCKSCALE IN
                                               </button>
                                            </span>

                                            <br/>

                                            <small class="text-muted text-uppercase">Truckscale OUT</small><br/>
                                            <span v-if="entry.truckscale_out">
                                                {{ moment(entry.truckscale_out.date) }}
                                            </span>
                                            <span class="text-uppercase" v-if="!entry.truckscale_out">
                                                <button class="disabled btn btn-outline-danger btn-sm text-uppercase">
                                                   NO TRUCKSCALE IN
                                               </button>
                                            </span>
                                        
                                        </div>
                                         <div class="col-sm-2">
                                             <br/>
                                            <span v-if="entry.truckscale_in">
                                                <a :class="{ 'btn-outline-success' : entry.sticker_in, 'btn-outline-danger' : !entry.sticker_in }" class="btn btn-sm mb-2" :href="'http://172.17.2.25:8080/RFID/' + cameraDate(entry.truckscale_in.date) + '/AC.' + cameraDate(entry.truckscale_in.date) + '.0000' + entry.truckscale_in_id + '-1.jpg'" :data-lightbox="entry.LogID" :data-title="'TIME IN - ' + moment(entry.truckscale_in.date)">                      
                                                    <i class="fa fa-camera" aria-hidden="true"></i> 
                                                    <span v-if="entry.sticker_in">
                                                        Matched
                                                    </span>
                                                    <span v-else>
                                                        Not Matched
                                                    </span>
                                                </a>
                                            </span>
                                            <br/>
                                            <span v-if="entry.truckscale_out">
                                                <a :class="{ 'btn-outline-success' : entry.sticker_out, 'btn-outline-danger' : !entry.sticker_out }" class="btn btn-sm mb-2" :href="'http://172.17.2.25:8080/RFID/' + cameraDate(entry.truckscale_out.date) + '/AC.' + cameraDate(entry.truckscale_out.date) + '.0000' + entry.truckscale_out_id + '-2.jpg'" :data-lightbox="entry.LogID" :data-title="'TIME IN - ' + moment(entry.truckscale_out.date)">                      
                                                    <i class="fa fa-camera" aria-hidden="true"></i> 
                                                    <span v-if="entry.sticker_out">
                                                        Matched
                                                    </span>
                                                    <span v-else>
                                                        Not Matched
                                                    </span>
                                                </a>
                                            </span>
                                        </div>
                                        <div class="col-sm-1 text-center">
                                             <small class="text-muted text-uppercase">Rendered Time</small><br/>
                                             <span v-if="entry.plant_in && entry.truckscale_out">
                                                    {{ dateDiff(entry.plant_in.date, entry.truckscale_out.date) }}
                                             </span>
                                             <span class="text-uppercase text-muted align-middle" style="margin-top: 10px;" v-else>
                                                 <button class="disabled btn btn-outline-dark text-uppercase btn-sm">
                                                    NO PAIR
                                                </button>
                                             </span>
                                        </div>
                                    </div>

                                </li>
                                <li v-if="paginateEntries.length == 0"  class="list-group-item">
                                    <div class="row p-3">
                                        <div class="col-sm-12 text-center text-muted display-4">
                                            <span>Nothing Found</span>
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
        
        setPage(pageNumber) {
            this.currentPage = pageNumber;         
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        moment(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        },

        cameraDate(date) {
            return moment(date).format('YYYYMMDD');
        },

        dateDiff(startTime, endTime) {
            var a = moment(startTime);   
            var b = moment(endTime);   
            return b.diff(a, 'hours');
       },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }
    },

    computed: {
        totalPages() {
          return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filteredEntries() {
            const vm = this;
            
            return _.filter(vm.entries, function (item) {
                return ~item.driver_name.toLowerCase().indexOf(vm.searchKey.trim().toLowerCase());
            });
        },

        paginateEntries() {

            var index = this.currentPage * this.itemsPerPage;
            var entries_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            } 
            
            return entries_array;
        }
    },

}
</script>

<style scoped>
    button {
        cursor: pointer;
    }
</style>