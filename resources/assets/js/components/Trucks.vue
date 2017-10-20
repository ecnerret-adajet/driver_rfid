<template>

 <div>
               <div clas="row">

                <div id="custom-search-input">
                    <div class="input-group col-sm-12 col-md-12 col-lg-12 mb-2 p-0">

                        <input type="text" class="  search-query form-control"  v-model="searchString" placeholder="Search" />
                        <span class="input-group-btn">
                        <button class="btn btn-danger" type="button">
                        <i class="fa fa-search"></i>
                        </button>
                       
                        </span>

                        <a class="btn btn-primary" :href="export_link">
                            Export as Excel
                        </a>



                    </div>
                       
                         

                </div>
            </div> <!-- end row -->

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
                                          </a> : <small class="badge badge-primary mr-2" v-for="driver in truck.drivers">{{ driver.cardholder.Name }}</small> <br/>
                                            
                                            <span class="text-muted"  v-for="hauler in truck.haulers">
                                               {{ hauler.name }}
                                            </span>
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

                                                <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="truckDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="truckDropdown">
                                                    <a :href="truck_link + truck.id + '/transfer'" class="dropdown-item">Transfer to 3PL</a>
                                                    <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#removeDriver-'+ truck.id">Remove Driver</a>
                                                    <a  href="javascript:void(0);" class="dropdown-item" data-toggle="modal" :data-target="'#truckModal-'+ truck.id" style="color: red">Deactivate</a>
                                                    
                                                    <span v-if="user_role == 'Administrator'">
                                                        <a :href="truck_link + truck.id + '/edit'" class="dropdown-item">Edit</a>
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
                         <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;" v-if="loading">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div>
                    </div>
                </div>


        <div v-for="truck in filteredTruck">

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
export default {

    props: {
        role: String,
    },

    data() {
        return {
            searchString: '',
            truck_link: '/driver_rfid/public/trucks/',
            export_link: '/driver_rfid/public/exportTrucks',
            trucks: [],
            user_role: this.role,
            loading: false,
            csrf: '',
        }
    },

    mounted() {
        this.csrf = window.Laravel.csrfToken;
    },

    created() {
        this.getTruck()
    },

    methods: {
        getAuth() {
            axios.get('/driver_rfid/public/getAuth')
            .then(response => this.auth = response.data);
        },

        getTruck() {
            this.loading = true
            axios.get('/driver_rfid/public/trucksJson')
            .then(response => {
                 this.trucks = response.data
                 this.loading = false
            });
        }
    },

    computed: {
        filteredTruck() {
            var trucks_array = this.trucks;
            var searchString = this.searchString;

            if(!searchString) {
                return trucks_array;
            }

            searchString = searchString.trim().toLowerCase();
    
            trucks_array = trucks_array.filter(function(item){

                var cardholder = item['drivers'].map((driver) => {
                    return driver.cardholder.Name.toLowerCase().indexOf(searchString) !== -1
                })


                if(item.plate_number.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return trucks_array;
        }
    }
}
</script>