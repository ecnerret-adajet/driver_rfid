<template>
    <div>        
            <div clas="row">
                <div id="custom-search-input mx-auto">
                    <div class="input-group col-sm-12 col-md-12 col-lg-12 mb-2 p-0">
                        <input type="text" class="  search-query form-control"  v-model="searchString" placeholder="Search" />
                        <span class="input-group-btn">
                        <button class="btn btn-danger" type="button">
                        <i class="fa fa-search"></i>
                        </button>
                        </span>
                    </div>
                </div>
            </div> <!-- end row -->

            <div class="row">
                <div class="col-sm-12">
                    <div v-if="!loading">
                        <ul class="list-group">
                            <li v-for="pickup in filteredPickup" class="list-group-item">
                                <div class="row">   
                                    <div class="col-sm-1">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <small class="text-muted" style="text-transform: uppercase">
                                            Driver Details    
                                        </small><br/>
                                        <span style="text-transform: uppercase">{{pickup.driver_name}}</span> 
                                        <br/>
                                        <span style="text-transform: uppercase">{{ pickup.plate_number }}</span>
                                        <br/>
                                        <span style="text-transform: uppercase">{{ pickup.company }}</span>
                                        <br/>
                                        <small class="text-muted" style="text-transform: uppercase">
                                            Created By:  
                                        </small>
                                        <br/>
                                        <span style="text-transform: uppercase">{{pickup.user.name}}</span> 
                                    </div>
                                    <div class="col-sm-4">
                                        <small class="text-muted" style="text-transform:uppercase">
                                            Created Date: 
                                        </small> <br/>
                                        <span>
                                            {{ moment(pickup.created_at) }}
                                        </span>
                                        <br/> 
                                         <small class="text-muted" style="text-transform:uppercase">
                                            Checkout Date:
                                        </small><br/>
                                        <span>
                                            <span v-if="pickup.deactivated_date">
                                                {{ moment(pickup.deactivated_date) }}
                                             </span>
                                             <span class="text-danger" v-else>
                                                NOT YET SERVED
                                             </span>
                                        </span>
                                        <br/>
                                        <small class="text-muted" style="text-transform:uppercase">
                                            DO NUMBER:
                                        </small><br/>
                                        <span>
                                            {{ pickup.do_number }}
                                        </span>
                                        <br/>
                                    </div>

                                    <div class="col-sm-2">
                                        <small class="text-muted" style="text-transform: uppercase">
                                            Cardholder:
                                        </small><br/>
                                        <small v-if="pickup.cardholder" style="text-transform: uppercase">{{ pickup.cardholder.Name }}</small>
                                        <small v-else>
                                            NO RFID YET
                                        </small><br/>
                                        <small class="text-muted" style="text-transform: uppercase">
                                            STATUS:
                                        </small><br/>
                                        <small v-if="pickup.cardholder" style="text-transform: uppercase">
                                            SERVED
                                        </small>
                                        <small v-else>
                                            <button class="btn btn-sm btn-outline-warning">
                                            NOT YET SERVED
                                            </button>
                                        </small>
                                    </div>

                                    <div class="col-sm-1 pull-right right">
                                        <span>
                                            <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">
                                                 <a :href="'unserved/' + pickup.id + '/edit'" class="dropdown-item">Update</a>
                                                 <a href="#" class="dropdown-item text-danger" data-toggle="modal" :data-target="'#pickupCancel-'+ pickup.id">Cancel Pickup</a>
                                            </div><!-- end dropdown -->
                                        </span>
                                    </div>


                                </div>
                            </li>
                            <li v-if="filteredPickup.length == 0"  class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <span class="text-muted">NO RECORD FOUND</span>
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
            </div><!-- end row -->


            <div v-for="pickup in filteredPickup">
            <!-- Deactivate Modal -->
            <div class="modal fade" :id="'pickupCancel-' + pickup.id" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
            <div class="modal-dialog" id="queueter">
                <div class="modal-content">
                <div class="modal-header">

                    <h6 class="modal-title" id="driverModalLabel">Cancel RFID</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                

                </div>
                <div class="modal-body text-center">

                                           
                    <em>Are you sure you want to proceed with this action?</em>
                

                </div>
                <div class="modal-footer">  
                    <form  method="post" :action="'/driver_rfid/public/pickups/unserved/'+pickup.id">
                        
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="_method" value="delete">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button> 
                    </form>  
                </div>
                    
                </div>
            </div>
            </div><!-- end modal -->
            </div>

    </div>
</template>
<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                searchString: '',
                loading: false,
                pickups: [],
                csrf: '',
            }
        },

        mounted() {
            this.csrf = window.Laravel.csrfToken;
        },


        created() {
            this.getPickup()
        },

        methods: {
            getPickup() {
                this.loading = true
                axios.get('/driver_rfid/public/getPickupData')
                .then(response => {
                    this.pickups = response.data
                    this.loading = false
                });
            },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            }

        },

        computed: {
            filteredPickup() {
                var pickup_array = this.pickups;
                var searchString = this.searchString;

                if(!searchString) {
                    return pickup_array;
                }

                searchString = searchString.trim().toLowerCase();

                pickup_array = pickup_array.filter(function(item) {
                    if(item.driver_name.toLowerCase().indexOf(searchString) !== -1) {
                        return item;
                    }
                })

                return pickup_array;
            }
        }


    }
</script>