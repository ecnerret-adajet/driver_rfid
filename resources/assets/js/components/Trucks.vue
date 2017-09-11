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
                                            {{truck.plate_number}}  : <small class="badge badge-primary mr-2" v-for="driver in truck.drivers">{{ driver.cardholder.Name }}</small>
                                            <span  v-for="hauler in truck.haulers">
                                               {{ hauler.name }}
                                            </span>
                                            <br/>
                                            <span v-for="driver in truck.drivers">
                                                 {{driver.name}}
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="badge badge-primary" v-if="truck.card !=  null">
                                                Sticker Assigned
                                            </span> 
                                        </div>
                                        <div class="col-sm-3 pull-right right">

                                            <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                <a :href="truck_link + truck.id + '/transfer'" class="btn btn-secondary btn-sm">Trasnfer</a>
                                                <a :href="truck_link + truck.id + '/edit'" class="btn btn-secondary btn-sm">Edit</a>
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
            truck_link: '/driver_rfid/public/trucks/',
            export_link: 'http://localhost/driver_rfid/public/exportTrucks',
            trucks: [],
            loading: false
        }
    },
    created() {
        this.getTruck()
    },

    methods: {
        getTruck() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/trucksJson')
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