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

                        

                       

                    </div>
                       
                         

                </div>
            </div> <!-- end row -->


    <div class="row">
        <div class="col-sm-12">
              <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendor Number</th>
                        <th>Server</th>
                    </tr>
                </thead>
                <tbody v-if="!loading">
                        <tr v-for="handler in filteredHandler">
                            <td>{{ handler.id }}</td>
                            <td>{{ handler.vendor_number }}</td>
                            <td>{{ handler.server_id }}</td>
                        </tr>
                        <tr class="center" style="display: flex; align-items: center; justify-content: center;" v-if="filteredHandler.length == 0">
                            <td class="center" rowspan="3">
                                NO RECORD FOUND
                            </td>
                        </tr>
                </tbody>
                <tbody v-if="loading">
                    <tr>
                        <td class="center" style=" display: flex; align-items: center; justify-content: center;" rowspan="3">
                         <div class="center-align" style="padding-top: 50px;">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div>
                        </td>
                    </tr>
                </tbody>
                </table>
        </div>
    </div>




</div>
</template>
<script>
export default {
    data() {
        return {
            searchString: '',
            loading: false,
            handlers: []
        }
    },
    created() {
        this.getHandlers()
    },
    methods: {
        getHandlers() {
            this.loading = true
            axios.get('/driver_rfid/public/handlerJson')
            .then(response => {
                this.handlers = response.data
                this.loading = false
            });
        }
    },
    computed: {
        filteredHandler() {
            
            var handler_array = this.handlers;
            var searchString = this.searchString;

            if(!searchString){
                return handler_array;
            }

            searchString = searchString.trim().toLowerCase();

            handler_array = handler_array.filter(function(item){
                if(item.vendor_number.toLowerCase().indexOf(searchString) !== -1){
                    return item;
                }
            })

            return handler_array;

        }
    }

}
</script>