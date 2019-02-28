<template>
<div>
    <p class="pt-2">
        <span v-show="driverPass" style="font-size: 10px" class="text-uppercase badge badge-warning">Pass</span>
        <span v-show="truckscaleIn" style="font-size: 10px" class="text-uppercase badge badge-warning">TS-In</span>
        <span v-show="submittedDr" style="font-size: 10px" class="text-uppercase badge badge-warning">DR-Received</span>
        <span v-show="assignedShipment" style="font-size: 10px" class="text-uppercase badge badge-warning">Shipped</span>
        <span v-show="truckscaleOut" style="font-size: 10px" class="text-uppercase badge badge-warning">TS-Out</span>
    </p>
</div>
</template>

<script>
export default {

    props: {
        item: Object
    },

    data() {
        return {
            driverPass: false,
            truckscaleIn: false,
            submittedDr: false,
            assignedShipment: false,
            truckscaleOut: false,
        }
    },

    mounted() {
        this.checkSequenceStatus()
    },

    methods: {
        checkSequenceStatus() {

            if(this.item.isTappedGateFirst === "1") {
                this.driverPass = true
            } else {
                this.driverPass = false
            }

            if(this.item.isDRCompleted != "0000-00-00") {
                this.submittedDr = true
            } else {
                this.submittedDr = false
            }

            if(this.item.shipment_number) {
                this.assignedShipment = true
            } else {
                this.assignedShipment = false
            }

        }
    }

}
</script>

