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
                <li v-for="hauler in filteredHauler" class="collection-item avatar">
                     
                    <span class="title">{{hauler.name}}</span>
                    <p>
                        {{ hauler.address }}
                    </p>
                    <p>
                        {{hauler.contact_number}} 
                    </p>

                    <p class="secondary-content right-align">
                        <a :href="hauler_link + hauler.id + '/edit'"><i class="material-icons">open_in_new</i></a><br/>
                        <span>
                        NUMBER OF TRUCK(S): {{ hauler.drivers.length  }}
                        </span>
                    </p>
                </li>
                <li v-if="filteredHauler.length == 0" class="collection-item avatar center-align">
                    <span class="title">NO HAULER FOUND</span>
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
            hauler_link: '/driver_rfid/public/haulers/',
            haulers: []
        }
    },
    created() {
        axios.get('http://localhost/driver_rfid/public/haulersJson')
        .then(response => this.haulers = response.data);
    },
    computed: {
        filteredHauler() {
            var haulers_array = this.haulers;
            var searchString = this.searchString;

            if(!searchString) {
                return haulers_array;
            }

            searchString = searchString.trim().toLowerCase();

            haulers_array = haulers_array.filter(function(item) {
                if(item.name.toLowerCase().indexOf(searchString) !== -1) {
                    return item;
                }
            })

            return haulers_array;
        }
    }
}
</script>