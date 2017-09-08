<template>

<div class="row">

    <div class="input-field col s6">
        <input type="text" name="vendor_description" class="validate"  v-model="searchVendor" >
        <label>Vendor Number</label>
        <div v-for="(vendor, v) in filteredVendor">
            <span class="red-text" v-if="emptyVendor">
                NO DATA YET
            </span>
            <span v-else>
                <span v-if="v == 0">
                    {{ vendor.vendor_name }}
                </span>
            </span>
        </div>
    </div>

      <div class="input-field col s6">
        <input type="text" name="subvendor_description" class="validate"  v-model="searchSubVendor" >
        <label>Subvendor Number</label>
        <div v-for="(subvendor, s) in filteredSubVendor">
            <span class="red-text" v-if="emptySubVendor">
                NO DATA YET
            </span>
            <span v-else>
                <span v-if="s == 0">
                    {{ subvendor.vendor_name }}
                </span>
            </span>
        </div>
    </div>


</div>


</template>
<script>
export default {
    data() {
        return {
            searchVendor: '',
            searchSubVendor: '',
            vendors: [],
            subvendors: [],
            emptySubVendor: false,
            emptyVendor: false,
        }
    },
    created() {
       this.getVendor()
       this.getSubvendor()
    },
    methods: {
        getVendor() {
             axios.get('http://localhost/driver_rfid/public/vendorsJson')
            .then(response => this.vendors = response.data);
        },
        getSubvendor() {
             axios.get('http://localhost/driver_rfid/public/subvendorJson')
            .then(response => this.subvendors = response.data);
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
            
            var subcon_array = this.subvendors;
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