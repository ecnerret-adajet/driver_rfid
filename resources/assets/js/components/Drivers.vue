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

                        <a :href="export_link" class="btn btn-primary">
                            Export as Excel
                        </a>

                       

                    </div>
                       
                         

                </div>
            </div> <!-- end row -->

                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="driver in filteredDriver" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">
                                             <img :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </div>
                                        <div class="col-sm-5">
                                            <a :href="'/driver_rfid/public/drivers/' + driver.id">{{driver.name}}</a> : <small>{{ driver.cardholder.Name }}</small>
                                            <br/>
                                            <span v-for="truck in driver.trucks">
                                                {{ truck.plate_number }}
                                            </span>
                                            <br/>
                                            <span v-for="hauler in driver.haulers">
                                                {{hauler.name}}
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="badge badge-primary" v-if="driver.card !=  null">
                                                Card Assigned
                                            </span> 
                                            <br/> 
                                            <span>
                                            COUNT LOGS: <strong> {{ driver.cardholder.logs.length == null ? '0' : driver.cardholder.logs.length }} </strong>
                                            </span>
                                            <br/>
                                            <span>
                                            COUNT UPDATE: <strong> {{ driver.update_count == null ? 0 : driver.update_count  }} </strong>
                                            </span>
                                        </div>
                                        <div class="col-sm-3 pull-right right">

                                             <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                <a :href="driver_link + driver.id + '/reassign'" class="btn btn-secondary btn-sm">Reassign</a>
                                                <a :href="driver_link + driver.id + '/edit'" class="btn btn-secondary btn-sm">Edit</a>
                                            </div>

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
  </div>


</template>

<script>
export default {
    data() {
        return {
            searchString: '',
            driver_link: '/driver_rfid/public/drivers/',
            avatar_link: 'http://localhost/driver_rfid/storage/app/',
            export_link: 'http://localhost/driver_rfid/public/exportDrivers',
            drivers: [],
            loading: false
        }
    },
    created() {
       this.getDrivers()
    },

    methods: {
        getDrivers() {
            this.loading = true
             axios.get('http://localhost/driver_rfid/public/driversJson')
            .then(response => {
                this.drivers = response.data
                this.loading = false
            });
        }
    },

    computed: {
        filteredDriver() {
            
            var drivers_array = this.drivers;
            var searchString = this.searchString;

            if(!searchString){
                return drivers_array;
            }

            searchString = searchString.trim().toLowerCase();

            drivers_array = drivers_array.filter(function(item){
                if(item.name.toLowerCase().indexOf(searchString) !== -1 || item.phone_number.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return drivers_array;

        }
    }
}
</script>