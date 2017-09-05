<template>
    <div>
        
         <div class="row">
            <div class="input-group search pull-right">
                <span class="input-group-addon opener">
                <a>
                <i class="material-icons">search</i>
                </a>
                </span>
                <input type="text" v-model="searchString"  class="form-control" placeholder="Search">
                <span class="input-group-addon">
                <a>
                <i class="material-icons">more_vert</i>
                </a>
                </span>
                <span class="input-group-addon opener">
                <a>
                <i class="material-icons">clear</i>
                </a>
                </span>
            </div>
        </div>


        <div class="had-container">

            <div v-if="!loading">
                <ul class="collection">
                    <li v-for="pick in filteredPickup" class="collection-item avatar">
                                <i v-if="pick.availability ==  1" class="material-icons circle white-text">brightness_1</i>
                               <i  v-else class="material-icons circle red-text">brightness_1</i>

                        

                        <span class="title">{{ pick.plate_number }}  : <small class="chip">{{pick.cardholder.Name}}</small></span>
                        <p>
                            {{ pick.driver_name }}
                        </p>
                        <p>
                            {{ pick.company }}
                        </p>
                      

                        <p class="secondary-content right-align">


                            <span v-for="(login, index) in pick.cardholder.logs_in">
                                 <span class="chip" v-if="checkDate(login.LocalTime) > checkDate(pick.created_at) && checkDate(login.LocalTime) <= addHour(pick.created_at)">
                                    {{ index}} - {{ login.LocalTime }}
                                 </span>
                            </span>

                            <!-- 
                                <span v-for="(logout, index) in pick.cardholder.logs_out">
                                    <span class="chip" v-if="index == 0">
                                        {{ moment(logout.LocalTime) }}
                                    </span>
                                </span> 
                            -->
                        
                            <span class="input-group-addon">
                                <a class="dropdown-button" href="javascript:void(0);" :data-activates="'dropdown' + pick.id"><i class="material-icons">more_vert</i></a>
                                <ul :id="'dropdown' + pick.id " class='dropdown-content'>
                                    <li><a href="#!">Edit Entry</a></li>
                                    <li><a href="#!">Deactivate</a></li>
                                </ul>
                            </span>

                            <!-- <a :href="driver_link + driver.id + '/edit'"><i class="material-icons">open_in_new</i></a><br/>
                            <span>
                            COUNT UPDATE: {{ driver.update_count == null ? 0 : driver.update_count  }}
                            </span> -->
                        </p>

                    </li>
                    <li v-if="filteredPickup.length == 0" class="collection-item avatar center-align">
                        <span class="title">NO RECORD FOUND</span>
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
        this.getPickups()
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