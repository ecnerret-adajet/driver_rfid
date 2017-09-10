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

                         <div class="dropdown pull-right">
                            <a class="btn btn-primary btn-block" href="javascript:void(0);" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" :href="export_link">Export as Excel</a>
                                </li>
                            </ul>
                        </div>


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
                                             <img :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </div>
                                        <div class="col-sm-5">
                                            {{truck.plate_number}}  : <small class="chip" v-for="driver in truck.drivers">{{ driver.cardholder.Name }}</small>
                                            <br/>
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
                                            <div class="dropdown pull-right">
                                                <a href="javascript:void(0);" data-toggle="dropdown">
                                                   <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a :href="truck_link + truck.id + '/edit'">
                                                         Edit Truck
                                                        </a>
                                                    </li>
                                                </ul>
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
            truck_link: '/driver_rfid/public/trucks/',
            export_link: 'http://localhost/driver_rfid/public/exportTrucks',
            trucks: [],
            loading: false
        }
    },
    created() {
        this.getTruck()
    },

    mounted(){
        $('.tooltipped').tooltip({delay: 50});
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