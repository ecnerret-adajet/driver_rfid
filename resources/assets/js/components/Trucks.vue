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
            <ul class="collection">
                <li v-for="truck in filteredTruck" class="collection-item avatar">
                     <!-- <img :src="avatar_link + truck.avatar" alt="" class="circle"> -->
                    <span class="title">{{truck.plate_number}}</span>
                    <p v-for="hauler in truck.haulers">
                        {{ hauler.name }}
                    </p>
                    <p v-for="driver in truck.drivers">
                        {{driver.name}} 
                    </p>

                    <p class="secondary-content right-align">
                        <a :href="truck_link + truck.id + '/edit'"><i class="material-icons">open_in_new</i></a><br/>
                        <!-- <span>
                        COUNT UPDATE: {{ truck.update_count == null ? 0 : truck.update_count  }}
                        </span> -->
                    </p>
                </li>
                <li v-if="filteredTruck.length == 0" class="collection-item avatar center-align">
                    <span class="title">NO TRUCK FOUND</span>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            searchString: '',
            truck_link: '/driver_rfid/public/trucks/',
            trucks: []
        }
    },
    created() {
        axios.get('http://localhost/driver_rfid/public/trucksJson')
        .then(response => this.trucks = response.data);
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
                if(item.plate_number.toLowerCase().indexOf(searchString) !== -1) {
                    return item;
                }
            })

            return trucks_array;
        }
    }
}
</script>