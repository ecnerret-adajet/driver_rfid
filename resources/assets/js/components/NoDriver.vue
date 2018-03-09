<template>

 <div>
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
                                          </a> : <small class="badge badge-primary mr-2" v-for="driver in truck.driver" v-if="driver.cardholder">{{ driver.cardholder.Name }}</small> <br/>
                                            
                                            <span class="text-muted"  v-for="h in truck.hauler">
                                               {{ h.name }}
                                            </span>

                                            <br/>
                                            
                                            <span v-for="d in truck.driver">
                                                 {{d.name}}
                                            </span>
                                            <span v-if="truck.driver == 0" style="color: red">
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

                                                <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="truckDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="truckDropdown">

                                                    <span v-if="user_role == 'Monitoring' || user_role == 'Administrator'">
                                                    <a :href="truck_link + truck.id + '/transfer'" class="dropdown-item" >Transfer to 3PL</a>
                                                    <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#removeDriver-'+ truck.id">Remove Driver</a>
                                                    <a :href="truck_link + truck.id + '/editInfo'" class="dropdown-item">Update Truck</a>
                                                    <!-- <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#truckModal-'+ truck.id" style="color: red">Deactivate</a> -->
                                                   
                                                   <span v-if="truck.reg_number">
                                                        <span v-if="truck.plate_number == truck.reg_number && truck.reg_number.indexOf('MV') !== -1">
                                                            <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#truckChange-'+ truck.id">Update Plate Number</a>
                                                        </span>
                                                   </span>
                                                   <div class="dropdown-divider"></div>
                                                   </span>
                                                   
                                                   
                                                    <span v-if="user_role == 'Administrator'">
                                                            <a :href="truck_link + truck.id + '/edit'" class="dropdown-item">Edit</a>
                                                            <div class="dropdown-divider"></div>
                                                    </span>

                                                    <span v-if="user_role == 'Administrator' || user_role == 'spc-monitoring'">
                                                        <!-- modal deactivation -->
                                                        <!-- <a  href="javascript:void(0);" class="dropdown-item text-danger" data-toggle="modal" :data-target="'#truckDeactivated-'+ truck.id">Deactive Truck</a> -->
                                                        
                                                        <!-- hyperlink to another page-->
                                                        <a v-if="truck.availability" :href="'inspects/deactivate/' + truck.id " class="dropdown-item text-danger">Deactivate Truck</a>
                                                        <a v-if="!truck.availability" :href="'inspects/activate/' + truck.id " class="dropdown-item text-success">Activate Truck</a>
                                                        <a :href="'inspects/show/' + truck.id " class="dropdown-item">View History</a>
                                                    </span>

                                                </div><!-- end dropdown -->
                                            
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
                    <span>{{ trucks.length }} Trucks</span>
                </div>
            </div>


        <div v-for="truck in filteredTruck">


            <!-- Change Plate Number Modal -->
            <div class="modal fade" :id="'truckChange-' + truck.id" tabindex="-1" role="dialog" aria-labelledby="truckModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="truckModalLabel">Change to official plate number</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                 <form  method="POST" class="bootstrap-modal-form" :action="'/driver_rfid/public/trucks/changePlateNumber/'+truck.id">
                 <input type="hidden" name="_token" :value="csrf">  
                    <div class="modal-body">

                                        
                <div class="form-group">
                    <label for="inputPlateNumber">Plate Number</label>
                    <input type="text" class="form-control" id="inputPlateNumber" name="plate_number"  placeholder="Enter New Plate Number" data-inputmask="'mask': 'AAA-9999'" data-mask required>
                     <small id="emailHelp" class="form-text text-muted">Please follow the format: AAA-000.</small>
                </div>
                

                </div>
                <div class="modal-footer">  
                   
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                     
                </div>
                </form> 
                </div>
            </div>
            </div>

            <!-- Deactivate Modal -->
            <div class="modal fade" :id="'truckModal-' + truck.id" tabindex="-1" role="dialog" aria-labelledby="truckModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="truckModalLabel">Deactivate Truck</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="POST" :action="'/driver_rfid/public/trucks/deactivate/'+truck.id">
                        <input type="hidden" name="_token" :value="csrf">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div>


              <!-- Remove Driver Modal -->
            <div class="modal fade" :id="'removeDriver-' + truck.id" tabindex="-1" role="dialog" aria-labelledby="truckModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="truckModalLabel">Remove Driver</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="POST" :action="'/driver_rfid/public/trucks/remove/'+truck.id">
                        <input type="hidden" name="_token" :value="csrf">  
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div>


        </div><!-- end modal forloop -->


        
  </div>

</template>

<script>
import VueContentPlaceholders from 'vue-content-placeholders';
import _ from 'lodash';

export default {

    props: ['user_role'],

    data() {
        return {
            searchString: '',
            truck_link: '/driver_rfid/public/trucks/',
            trucks: [],
            loading: false,
            csrf: '',
            itemsPerPage: 5,
            currentPage: 0,
        }
    },

    mounted() {
        this.csrf = window.Laravel.csrfToken;
    },

    components: {
        VueContentPlaceholders,
    },

    created() {
        this.getTruck()
    },

    methods: {
        getTruck() {
            this.loading = true
            axios.get('/driver_rfid/public/noDriverJson')
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
            var trucks_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            } 

            if(this.currentPage == -1){
                this.currentPage = 0;
            }
            
            return trucks_array;
        }

  
    }
}
</script>
