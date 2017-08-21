<template>
    <div>

        <div class="row">
            <div class="input-group search pull-right">
                <span class="input-group-addon opener">
                <i class="material-icons">search</i>
                </span>
                <input type="text" v-model="searchString"  class="form-control" placeholder="Search">
                <span class="input-group-addon">
                <i class="material-icons">more_vert</i>
                </span>
                <span class="input-group-addon opener">
                <i class="material-icons">clear</i>
                </span>
            </div>
        </div>

        <div class="had-container">

            <div v-if="!loading">
                <ul class="collection">
                    <li v-for="driver in filteredDriver" class="collection-item avatar">
                        <img :src="avatar_link + driver.avatar" alt="" class="circle">
                        <span class="title">{{driver.name}} : <small>{{ driver.cardholder.Name }}</small></span>
                        <p v-for="truck in driver.trucks">
                            {{ truck.plate_number }}
                        </p>
                        <p v-for="hauler in driver.haulers">
                            {{hauler.name}}
                        </p>
                      

                        <p class="secondary-content right-align">
                             <span class="chip red white-text" v-if="driver.card !=  null">
                                Card Assigned
                            </span>        
                            <a :href="driver_link + driver.id + '/edit'"><i class="material-icons">open_in_new</i></a><br/>
                            <span>
                            COUNT UPDATE: {{ driver.update_count == null ? 0 : driver.update_count  }}
                            </span>
                        </p>
                    </li>
                    <li v-if="filteredDriver.length == 0" class="collection-item avatar center-align">
                        <span class="title">NO DRIVER FOUND</span>
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
</template>

<script>
export default {
    data() {
        return {
            searchString: '',
            driver_link: '/driver_rfid/public/drivers/',
            avatar_link: 'http://localhost/driver_rfid/storage/app/',
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
                if(item.name.toLowerCase().indexOf(searchString) !== -1 || item.cardholder.Name.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return drivers_array;

        }
    }
}
</script>