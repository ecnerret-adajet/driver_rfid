<template>
    <!-- <download-excel
            class   = "float-right btn btn-primary disabled hoverable"
            :data   = "entries"
            :fields = "json_fields"
            name    = "filename.xls">
            Export Entries
    </download-excel> -->
    <button class="btn float-right btn-primary disabled hoverable">
        Export Entries
    </button>
</template>
<script>
import JsonExcel from 'vue-json-excel'
import moment from 'moment';
export default {

    props: {
        entries: Array,
    },

    components: {
        downloadExcel: JsonExcel
    },

    data() {
        return {
            json_fields: {
                'Driver Name': 'driver',
                'Plate Number': 'plate',
                'Hauler Name': 'hauler',
                'Driver pass': 'driverpass',
                'Last Submitted DR': 'last_dr_date',
                'Queue' : 'queue_time',
                'Shipment Date' : 'shipment',
                'Company' : 'company',
                'Plant_in' : 'truck_plant_in',
                'SAP_loading_start' : 'sap_loading_start',
                'SAP_loading end' : 'sap_loading_end',
                'Truckscale_in' : 'ts_time_in',
                'Truckscale_out' : 'ts_time_out',
                'Plant out' : 'gate_time_out',
            },
        }
    },

    methods: {
        parseDate(date) {
            if(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            } else {
                return null;
            }
        },
    },

    computed: {
        test() {
            return 'terrence'
        }
    }
}
</script>
<style scoped>
    .hoverable {
        cursor: not-allowed;
    }
</style>

