<template>
    <div>

          <!-- Icon Cards -->
        <div class="row">
          <div class="col-xl-4 col-sm-4 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                 All Pickup Logs
                </div>
                <div v-if="!is_loading">
                    <h3>
                 {{ pickupValue.all_pickups }}
                    </h3>
                </div>
                 <div v-if="is_loading">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                        <svg class="spinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </div>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
              </a>
            </div>
          </div>
          <div class="col-xl-4 col-sm-4 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                    Current Pickup
                </div>
               <div v-if="!is_loading">
                    <h3>
                      {{ pickupValue.current_pickup }}
                    </h3>
                </div>
                 <div v-if="is_loading">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                        <svg class="spinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </div>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
              </a>
            </div>
          </div>
          <div class="col-xl-4 col-sm-4 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                   Available Card
                </div>
                 <div v-if="!is_loading">
                    <h3>
                   {{ pickupValue.available_card }}
                    </h3>
                </div>
                 <div v-if="is_loading">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                        <svg class="spinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </div>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
              </a>
            </div>
          </div>
        </div>


          <div clas="row">

                <div id="custom-search-input">
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
                                <li v-for="pick in filteredPickup" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">
                                            <i class="fa fa-circle fa-2x" style="color: #2ecc71" aria-hidden="true" v-if="pick.availability == 1"></i>
                                            <i class="fa fa-circle fa-2x" style="color: #e74c3c" aria-hidden="true" v-else></i>                                        
                        
                                        </div>
                                        <div class="col-sm-5">
                                            {{ pick.plate_number }} : <small class="badge badge-primary">{{ pick.cardholder.Name }}</small>
                                            <br/>
                                            {{ pick.driver_name }}
                                            <br/>
                                            {{ pick.company }}
                                        </div>
                                        <div class="col-sm-3">
                                             <span v-for="(login, index) in pick.cardholder.logs_in">
                                                <span class="badge badge-primary" v-if="checkDate(login.LocalTime) > checkDate(pick.created_at) && checkDate(login.LocalTime) <= addHour(pick.created_at)">
                                                   {{ moment(login.LocalTime) }}
                                                </span>
                                            </span>
                                            <br/> 
                                            <span v-for="(logout, index) in pick.cardholder.logs_out">
                                                <span class="badge badge-warning" v-if="index == 0">
                                                    {{ moment(logout.LocalTime) }}
                                                </span>
                                            </span> 
                                        </div>
                                        <div class="col-sm-3 pull-right right">

                                            
                                                    <a class="btn btn-primary btn-sm pull-right" :href="pickup_link + pick.id + '/edit'">Edit</a>
                                            
                                        </div>
                                    </div>
                                </li>
                                <li v-if="filteredPickup.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO RECORD FOUND</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div><!-- end loading -->
                         <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;" v-if="loading">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div>
                    </div>
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
            is_loading: false,
            pickups: [],
            pickupValue: [],
            pickup_link: '/driver_rfid/public/pickups/',
        }
    },

    created() {
        this.getPickups()
        this.getPickupValue()
    },

    methods: {
        getPickups() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/pickupsJson')
            .then(response => {
                this.pickups = response.data
                this.loading = false
            });
        },

        getPickupValue()
        {
            this.is_loading = true
            axios.get('http://localhost/driver_rfid/public/pickupsStatus')
            .then(response => {
                this.pickupValue = response.data
                this.is_loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM  d, Y h:m:s A');
        },

        checkDate(date) {
            return moment(date).format('MMMM  d, Y h:m:s A');
        },

        addHour(date) {
            return moment(date).add(1, 'hours').format('MMMM  d, Y h:m:s A');
        }
    },

    computed: {

        filteredPickup() {
            
            var pickup_array = this.pickups;
            var searchString = this.searchString;

            if(!searchString){
                return pickup_array;
            }

            searchString = searchString.trim().toLowerCase();

            pickup_array = pickup_array.filter(function(item){
                if(item.plate_number.toLowerCase().indexOf(searchString) !== -1 || item.cardholder.Name.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return pickup_array;

        }

    }
}
</script>