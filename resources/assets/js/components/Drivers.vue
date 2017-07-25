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
                <li v-for="driver in filteredDriver" class="collection-item avatar">
                    <i class="material-icons circle">folder</i>
                    <span class="title">{{driver.name}}</span>
                    <p>First Line <br>
                        Second Line
                    </p>
                    <a href="#!" class="secondary-content"><i class="material-icons">open_in_new</i></a>
                </li>
                <li v-if="filteredDriver.length == 0" class="collection-item avatar center-align">
                    <span class="title">NO DRIVER FOUND</span>
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
             drivers: []
        }
    },
    created() {
        axios.get('http://localhost/driver_rfid/public/driversJson')
        .then(response => this.drivers = response.data);
    },
    computed: {
        filteredDriver() {
            
            var articles_array = this.drivers;
            var searchString = this.searchString;

            if(!searchString){
                return articles_array;
            }

            searchString = searchString.trim().toLowerCase();

            articles_array = articles_array.filter(function(item){
                if(item.name.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return articles_array;

        }
    }

}
</script>