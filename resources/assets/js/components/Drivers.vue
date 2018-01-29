<template>

  <div>
            
            <div class="form-row mb-2 mt-2">
               
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <select name="age" class="form-control disabled" v-model="searchHauler">
                            <option value="">All Haulers...</option>
                            <option v-for="hauler in haulers" :value="hauler.name">{{ hauler.name }}</option>
                        </select>
                    </div>
                </div>

            </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="driver in filteredDriver" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">

                                        <span v-if="driver.image">
                                                <img :src="avatar_link + driver.image.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </span>
                                        <span v-else>
                                            <img :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </span>
                                            
                                        
                                        </div>
                                        <div class="col-sm-5">
                                            <a :href="'/driver_rfid/public/drivers/' + driver.id"  style="text-transform: upppercase">{{driver.name}}</a> : <small v-if="driver.cardholder">{{ driver.cardholder.Name }}</small>
                                            <br/>
                    
                                            <span v-if="driver.truck" v-for="t in driver.truck">
                                               {{ t.plate_number }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO TRUCK
                                            </span>

                                            <br/>
                                            <span v-if="driver.hauler" v-for="h in driver.hauler">
                                               {{ h.name }}
                                            </span>
                                             <span v-else class="text-danger">
                                                    NO HAULER
                                            </span>
                                      
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="badge badge-primary" v-if="!driver.card">
                                                Card Assigned
                                            </span> 
                                        </div>
                                        <div class="col-sm-3 pull-right right">
                                        

                                         <span v-if="driver.availability == 1 || driver.print_status == 1 && driver.notif_status == 0">
                                          <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">

                                                 <!-- <span v-for="haulerx in driver.hauler">
                                                    <span v-for="truckx in driver.truck">
                                                -->
                                                <span v-if="driver.card !=  null">
                                                    <a :href="driver_link + driver.id + '/reassign'" class="dropdown-item">Reassign Truck</a>
                                                </span>
                                                <span v-if="driver.card ==  null">
                                                    <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#noCardAssigned-'+ driver.id" style="color: red">Reassign Truck</a>
                                                </span>
                                                    <a :href="driver_link + driver.id + '/editInfo'" class="dropdown-item">Update Info</a>
                                                  <!-- 
                                                    </span>
                                                 </span>  -->

                                                <!-- <a :href="driver_link + 'lostCard/' + driver.id" class="dropdown-item">Reprint Card</a> -->
                                                <a :href="driver_link + 'reprint/' + driver.id" class="dropdown-item">Reprint Card</a>
                                                <span v-if="driver.availability == 1">
                                                <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#driverModal-'+ driver.id" style="color: red">Deactivate</a>
                                                </span>
                                                <span v-if="driver.availability == 0">
                                                <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#driverModalActivate-'+ driver.id">Activate</a>
                                                </span>
                                                <span v-if="user_role == 'Administrator'">
                                                     <div class="dropdown-divider"></div>
                                                    <a :href="driver_link + driver.id + '/edit'" class="dropdown-item">Edit</a>
                                                </span>

                                            </div><!-- end dropdown -->
                                        </span>
                                        <span v-if="driver.availability == 0 && driver.print_status == 1 && driver.notif_status == 1">
                                              <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                 <span v-if="user_role == 'Administrator' || user_role == 'Approver'">
                                                    <a  href="javascript:void(0);" class="btn btn-outline-primary btn-sm ml-2" data-toggle="modal" :data-target="'#driverModalActivate-'+ driver.id">Activate</a>
                                                 </span>
                                                 <span v-else>
                                                    <button class="btn btn-outline-danger btn-sm disabled">INACTIVE</button>
                                                 </span>
                                              </div>
                                        </span>
                                        
                                        <span v-if="user_role == 'Administrator' || user_role == 'Monitoring'">
                                            <span v-if="driver.confirm">

                                                <a v-if="driver.confirm.status == 'Disapprove' && driver.confirm.classification == 'New Driver'" class="pull-right btn btn-outline-danger btn-sm" :href="'/driver_rfid/public/drivers/disapproved/' + driver.id">Update Details</a>
                                                <a v-if="driver.confirm.status == 'Disapprove' && driver.confirm.classification == 'Update Driver'" class="pull-right btn btn-outline-warning btn-sm text-warning" data-toggle="modal" :data-target="'#reverseDisapproved-'+ driver.id">Reverse Disapproved</a>
                                            
                                            </span>
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
                                <li v-if="filteredDriver.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO DRIVER FOUND</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                         <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;" v-if="loading">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                
                    <!-- <v-paginator :resource_url="resource_url" @update="updateResource"></v-paginator> -->

                    </div>
                </div>




        <div v-for="driver in filteredDriver">


            <!-- Deactivate Modal -->
            <div class="modal fade" :id="'driverModal-' + driver.id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="driverModalLabel">Deactivate RFID</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="POST" :action="'/driver_rfid/public/drivers/deactivate/'+driver.id">
                        <input type="hidden" name="_token" :value="csrf">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div><!-- end modal -->


            <!-- Activate Modal -->
            <div class="modal fade" :id="'driverModalActivate-' + driver.id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="driverModalLabel">Activate RFID</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="POST" :action="'/driver_rfid/public/drivers/activate/'+driver.id">
                        <input type="hidden" name="_token" :value="csrf">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div><!-- end modal -->


            <!-- Reverse Disapproved Modal -->
            <div class="modal fade" :id="'reverseDisapproved-' + driver.id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="driverModalLabel">Reverse Disapproved Driver</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="POST" :action="'/driver_rfid/public/drivers/reverseDisapproved/'+driver.id">
                        <input type="hidden" name="_token" :value="csrf">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div><!-- end modal -->


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
// import VueResource from 'vue-resource'
// import VuePaginator from 'vuejs-paginator'

// Vue.use(VueResource)

export default {

    props: ['user_role'],

//      components: {
//     VPaginator: VuePaginator
//   },

    data() {
        return {
            searchString: '',
            searchHauler: '',
            driver_link: '/driver_rfid/public/drivers/',
            avatar_link: '/driver_rfid/public/storage/',
            drivers: [],
            haulers: [],
            loading: false,
            csrf: '',
            // resource_url: 'http://localhost/driver_rfid/public/driversJson'
        }
    },
    
    mounted() {
        this.csrf = window.Laravel.csrfToken;
    },

    created() {
       this.getDrivers()
       this.getHaulers()
    },

    methods: {
        getDrivers() {
            this.loading = true
             axios.get('/driver_rfid/public/driversJson')
            .then(response => {
                this.drivers = response.data
                this.loading = false
            });
        },

        getHaulers() {
            axios.get('/driver_rfid/public/haulersJson')
            .then(response => this.haulers = response.data);
        },

    //     updateResource(data){
    //   this.animals = data
    // }

    },

    computed: {
        filteredDriver() {
            
            var drivers_array = this.drivers;
            var searchString = this.searchString;
            var searchHauler = this.searchHauler;

            if(!searchString && !searchHauler) {
                return drivers_array;
            }

            searchString = searchString.trim().toLowerCase();

            drivers_array = drivers_array.filter(function(item){
                // return item.data.name.toLowerCase().indexOf(searchString) !== -1 && item.hauler.indexOf(searchHauler)  !== -1;
                return item.name.toLowerCase().indexOf(searchString) !== -1;
            })
          

            return drivers_array;

        }
    }
}
</script>