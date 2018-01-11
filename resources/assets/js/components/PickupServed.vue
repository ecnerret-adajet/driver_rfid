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
                                    <div class="col-sm-4"><br/>
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
                                            NOT YET SERVE
                                            </button>
                                        </small>
                                    </div>

                                    <div class="col-sm-1 pull-right right">
                                        <span>
                                            <a class="dropdown pull-right btn btn-outline-secondary disabled" href="#" id="driverDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="driverDropdown">
                                                 <a href="#" class="dropdown-item">Details</a>
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
            
    </div>
</template>
<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                searchString: '',
                loading: false,
                pickups: []
            }
        },

        created() {
            this.getPickup()
        },

        methods: {
            getPickup() {
                this.loading = true
                axios.get('/driver_rfid/public/getPickupWithCardholder')
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