<template>

  <div>
               <div clas="row">

                <div id="custom-search-input">
                    <div class="input-group col-sm-11">
                        <input type="text" class="  search-query form-control"  v-model="searchString" placeholder="Search" />
                        <span class="input-group-btn">
                        <button class="btn btn-danger" type="button">
                        <span class=" glyphicon glyphicon-search"></span>
                        </button>
                        </span>


                    </div>
                    <div class="col-sm-1">
                        <div class="dropdown show">
                            <a class="btn btn-secondary dropdown-toggle" href="https://example.com" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" :href="export_link">Export as Excel</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- end row -->

                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li class="list-group-item">
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
                                        <div class="col-sm-3 pull-right right">
                                            <span class="badge badge-default" v-if="driver.card !=  null">
                                                Card Assigned
                                            </span>        
                                            <a :href="driver_link + driver.id + '/edit'"><i class="material-icons">open_in_new</i></a> <br/>
                                        </div>
                                        <div class="col-sm-3">
                                            <span>
                                            COUNT LOGS: <strong> {{ driver.cardholder.logs.length == null ? '0' : driver.cardholder.logs.length }} </strong>
                                            </span>
                                            <br/>
                                            <span>
                                            COUNT UPDATE: <strong> {{ driver.update_count == null ? 0 : driver.update_count  }} </strong>
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
                         <div class="center-align" style="padding-top: 50px" v-if="loading">
                            <div class="preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
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
                if(item.name.toLowerCase().indexOf(searchString) !== -1 || item.cardholder.Name.toLowerCase().indexOf(searchString) !== -1 || item.phone_number.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return drivers_array;

        }
    }
}
</script>