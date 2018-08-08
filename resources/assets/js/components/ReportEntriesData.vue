<template>
    <download-excel
            class   = "float-right btn btn-primary"
            :data   = "entries"
            :fields = "json_fields"
            :name    = "reportName">
            Export Entries
    </download-excel>
    <!-- <button class="btn float-right btn-primary disabled hoverable">
        Export Entries
    </button> -->
</template>
<script>
import JsonExcel from 'vue-json-excel'
import moment from 'moment';
export default {

    props: {
        entries: Array,
        location: Number,
        date: String,
    },

    components: {
        downloadExcel: JsonExcel
    },

    data() {
        return {
            json_fields: {
                'DRIVER NAME': 'driver',
                'PLATE NUMBER': 'plate',
                'HAULER NAME': 'hauler',
                'DRIVER-PASS': 'driverpass',
                'LAST-SUBMITTED-DR': 'last_dr_date',
                'QUEUE' : 'queue_time',
                'SHIPMENT-DATE' : 'shipment',
                'COMPANY' : 'company',
                'PLANT-IN' : 'truck_plant_in',
                'TRUCKSCALE-IN' : 'ts_time_in',
                'TRUCKSCALE-OUT' : 'ts_time_out',
                'PLANT-OUT' : 'gate_time_out',
                'SAP-TRUCKSCALE-IN' : 'sap_ts_in',
                'SAP-TRUCKSCALE-OUT' : 'sap_ts_out',
                'SAP-LOADING-START' : 'sap_loading_start',
                'SAP-LOADING-END' : 'sap_loading_end',
                'DRIVER-PASS TO QUEUE': 'driverpass_to_queue',
                'QUEUE TO SHIPMENT': 'queue_to_shipment',
                'SHIPMENT TO PLANT_IN': 'shipment_to_plant_in',
                // 'PLANT IN TO TS_IN': 'plant_in_to_ts_in',
                'SAP_TS_IN TO SAP-LOADING-START': 'sap_ts_in_to_sap_loading_start',
                'SAP-LOADING-START TO SAP-LOADING-END': 'sap_loading_start_to_sap_loading_end',
                'SAP-LOADING-END TO SAP_TS_OUT': 'sap_loading_end_to_sap_ts_out',
                'SAP_TS_OUT TO PLANT_OUT': 'sap_ts_out_to_plant_out',


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
        locationName() {
            if(this.location == 1) {
                return 'Manila'
            } else if (this.location == 2) {
                return 'Lapaz'
            } else if(this.location == 3) {
                return 'Bataan'
            } else {
                return 'location'
            }
        },
        reportName() {
            return `${this.locationName}-${this.date}`;
        }
    }
}
</script>
<style scoped>
    .hoverable {
        cursor: not-allowed;
    }
</style>

