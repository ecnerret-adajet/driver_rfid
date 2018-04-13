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
                                <li v-for="truck in filteredTruck" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">

                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                            </span>
                                        
                                        </div>
                                        <div class="col-sm-5">
                                           <a :href="truck_link + truck.id "> 
                                               <span v-if="truck.reg_number == null">
                                                    {{ truck.plate_number }}
                                                </span>
                                                <span v-else>
                                                    {{ truck.reg_number }}
                                                </span>
                                          </a> : <small class="badge badge-primary mr-2" v-for="driver in truck.drivers">
                                                    <span v-if="driver.cardholder">
                                                        {{ driver.cardholder.Name }}
                                                    </span>
                                                </small> <br/>
                                            
                                            <span class="text-muted"  v-for="hauler in truck.haulers">
                                               {{ hauler.name }}
                                            </span>

                                            <!-- {{ truck.hauler.map(a => a.name) }} -->

                                            <br/>
                                            
                                            <span v-for="driver in truck.drivers">
                                                 {{driver.name}}
                                            </span>
                                            <span v-if="truck.drivers == 0" style="color: red">
                                                NO DRIVER
                                            </span>

                                        </div>
                                        <div class="col-sm-3">
                                            <span class="badge badge-primary" v-if="truck.card !=  null">
                                                Sticker Assigned
                                            </span> 

                                            <span v-if="truck.availability == 1">
                                                <i class="fa fa-circle" style="color:green" aria-hidden="true"></i>                                            
                                            </span>
                                            <span v-if="truck.availability == 0">
                                                <i class="fa fa-circle" style="color:red" aria-hidden="true"></i> 
                                            </span>
                                        
                                        </div>
                                        <div class="col-sm-3 pull-right right">
                                              <div class="btn-group pull-right" role="group" aria-label="Basic example"  v-for="driver in truck.drivers">
                                                    <span v-if="!driver.cardholder">
                                                        <button class="btn btn-sm btn-outline-danger disabled">
                                                            PENDING FOR APPROVAL
                                                        </button>
                                                    </span>
                                                </div>
                                        </div>

                                        
                                    </div>
                                </li>
                                <li v-if="filteredTruck.length == 0"  class="list-group-item">
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
                    <span>{{ trucks.length }} Drivers</span>
                </div>
            </div>

        
  </div>

</template>
<script>
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';
    export default {
        props: ['user'],

         components: {
            VueContentPlaceholders,
        },

        data(){
            return {
                searchString: '',
                truck_link: '/driver_rfid/public/trucks/',
                trucks: [],
                loading: false,
                csrf: '',
                currentPage: 0,
                itemsPerPage: 5,
            }
        },

    mounted() {
        this.csrf = window.Laravel.csrfToken;
    },

    created() {
        this.getTruck()
    },

    methods: {
        getTruck() {
            this.loading = true
            axios.get('/driver_rfid/public/users/truck/hauler/' + this.user)
            .then(response => {
                 this.trucks = response.data
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
            
            return _.filter(vm.trucks, function (item) {
                return ~item.plate_number.toLowerCase().indexOf(vm.searchString.trim().toLowerCase());
            });
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filteredTruck() {
            
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