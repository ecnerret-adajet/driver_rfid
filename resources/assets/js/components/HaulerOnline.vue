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
                                <li v-for="(driver,d) in filteredDrivers" :key="d" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">
                                        
                                            <img v-if="driver.image" :src="avatar_link + driver.image.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                            <img v-else :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        
                                        </div>
                                        <div class="col-sm-5">
                                            <!-- <a :href="'/drivers/' + driver.id"  style="text-transform: upppercase">{{driver.name}}</a> :  -->
                                            <span style="text-transform: upppercase">{{driver.name}}</span> : 

                                            <small v-if="driver.cardholder">{{ driver.cardholder.Name }}</small>
                                            <br/>
                                            <span v-for="(truck,t) in driver.trucks" :key="t">
                                                <span v-if="truck.reg_number == null">
                                                    {{ truck.plate_number }} 
                                                </span>
                                                <span v-else>
                                                    {{ truck.reg_number }}
                                                </span>
                                            </span>
                                            <br/>
                                            <span v-for="(hauler, index) in driver.haulers" :key="index">
                                                <span v-if="index == 0">
                                                    {{ hauler.name }} 
                                                    <span v-if="hauler.name == null">
                                                        NO HAULER ASSIGNED
                                                    </span>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="badge badge-primary" v-if="driver.card !=  null">
                                                Card Assigned
                                            </span> 
                                            <br/> 
                                            <span>
                                            COUNT LOGS: <strong v-if="driver.cardholder"> {{ driver.cardholder.logs.length == null ? '0' : driver.cardholder.logs.length }} </strong>
                                            </span>
                                            <br/>
                                            <!-- <span>
                                            COUNT UPDATE: <strong> {{ driver.update_count == null ? 0 : driver.update_count  }} </strong>
                                            </span> -->
                                        </div>
                                        <div class="col-sm-3 pull-right right">
                                        

                                         <span v-if="driver.availability == 1 || driver.print_status == 1 && driver.notif_status == 0">
                                          <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">

                                                 <!-- <span v-for="hauler in driver.haulers">
                                                    <span v-for="truck in driver.trucks"> -->
                                                        <span v-if="driver.card !=  null">
                                                            <a :href="driver_link + driver.id + '/online/reassign'" class="dropdown-item">Reassign Truck</a>
                                                        </span>
                                                         <span v-if="driver.card ==  null">
                                                           <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#noCardAssigned-'+ driver.id" style="color: red">Reassign Truck</a>
                                                        </span>
                                                    <!-- </span>
                                                 </span>  -->


                                            </div><!-- end dropdown -->
                                        </span>
                                        <span v-if="driver.availability == 0 && driver.print_status == 1 && driver.notif_status == 1">
                                              <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                  <button class="btn btn-sm btn-outline-danger disabled">
                                                    PENDING FOR APPROVAL
                                                </button>
                                                    <!-- <a  href="javascript:void(0);" class="btn btn-outline-primary btn-sm" data-toggle="modal" :data-target="'#driverModalActivate-'+ driver.id">Activate</a> -->
                                              </div>
                                        </span>
                                                                                        

                                        <span v-if="driver.availability == 1">
                                            <i class="fa fa-circle" style="color:green" aria-hidden="true"></i>                                            
                                        </span>
                                        <span v-if="driver.availability == 0">
                                            <i class="fa fa-circle" style="color:red" aria-hidden="true"></i> 
                                        </span>
                                        
                                        </div>
                                    </div>
                                </li>
                                <li v-if="filteredDrivers.length == 0"  class="list-group-item">
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
                    <span>{{ drivers.length }} Drivers</span>
                </div>
            </div>



            <div v-for="driver in filteredDrivers">
                <!-- No Card Modal -->
                <div class="modal fade" :id="'noCardAssigned-' + driver.id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="queueter">
                    <div class="modal-content">
                    <div class="modal-header">

                        <h6 class="modal-title" id="driverModalLabel">No Card Assigned</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    

                    </div>
                    <div class="modal-body text-center">

                                            
                        <em>The selected driver has no card assigned, Please contact support for assistance.</em>
                    

                    </div>
                    <div class="modal-footer">  
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Confirm</button>
                    </div>
                        
                    </div>
                </div>
                </div><!-- end no card assigned -->
            </div><!-- end modal forloop -->

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

         data() {
             return {
                searchString: '',
                driver_link: '/drivers/',
                avatar_link: '/storage/',
                loading: false,
                drivers: [],
                csrf: '',
                currentPage: 0,
                itemsPerPage: 5,
             }
         },

        mounted() {
            this.csrf = window.Laravel.csrfToken;
        },

         created(){
             this.getDriversHauler()
         },

         methods: {
             getDriversHauler(){
                 this.loading = true
                 axios.get('/users/driver/hauler/'+ this.user)
                 .then(response => {
                     this.drivers = response.data
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
            
            return _.filter(vm.drivers, function (item) {
                return ~item.name.toLowerCase().indexOf(vm.searchString.trim().toLowerCase());
            });
        },

        totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
        },

        filteredDrivers() {

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