<template>



    <div class="input-field col s6">
        <input type="text" name="vendor_description" class="validate"  v-model="searchVendor" >
        <label>Vendor Number</label>
        <div v-for="(vendor, index) in filteredVendor">
            <span class="red-text" v-if="emptyVendor">
                NO DATA YET
            </span>
            <span v-else>
                <span v-if="index == 0">
                    {{ vendor.vendor_name }}
                </span>
            </span>
        </div>
    </div>








</template>
<script>
export default {
    data() {
        return {
            searchVendor: '',
            searchSubVendor: '',
            emptySubVendor: false,
            emptyVendor: false,
            vendors: []
        }
    },
    created() {
       this.getVendor()
    },
    methods: {
        getVendor() {
             axios.get('http://localhost/driver_rfid/public/vendorsJson')
            .then(response => this.vendors = response.data);
        }
    },
    computed: {
        filteredVendor() {
            
            var vendors_array = this.vendors;
            var searchVendor = this.searchVendor;
            var onEmpty =  this.emptyVendor;

            searchVendor = searchVendor.trim().toLowerCase();

            if(!searchVendor){
                return onEmpty = true;
            }

            vendors_array = vendors_array.filter(function(item){
                if(item.vendor_number.toLowerCase().indexOf(searchVendor) !== -1){
                    return item;
                }
            })

            return vendors_array;

          
        },

        filteredSubVendor() {
            
            var subcon_array = this.vendors;
            var searchSubVendor = this.searchSubVendor;
            var onEmpty =  this.emptySubVendor;

            searchSubVendor = searchSubVendor.trim().toLowerCase();

            if(!searchSubVendor){
                return onEmpty = true;
            }

            subcon_array = subcon_array.filter(function(item){
                if(item.vendor_number.toLowerCase().indexOf(searchSubVendor) !== -1){
                    return item;
                }
            })

            return subcon_array;

          
        }
    }
}
</script>