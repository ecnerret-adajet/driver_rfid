<template>
     <div class="modal fade" id="assignRFID" tabindex="-1" role="dialog" aria-labelledby="assignRFIDLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="assignRFIDLabel">Assign RFID</h5>
            <button type="button" class="close" @click="closeForm" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <label>Plate Number</label>
                <input type="text" disabled class="form-control" id="name" :value="truck.plate_number">
            </div>

             <div class="form-group" :class="{ ' has-danger' : errors.card_list }">
                <label>Truck RFID</label>
                <select  :disabled="isRfidAssgined" :class="{ 'is-invalid' : errors.card_list }" class="form-control" v-model="toSubmit.card_list">
                    <option value=""   selected>All RFID</option>
                    <option v-for="(rfid,c) in rfids" :key="c"  selected :value="rfid.CardID">{{ rfid.full_deploy }}</option>
                </select>
                <div v-if="errors.card_list" class="invalid-feedback">{{ errors.card_list[0] }}</div>
                <div v-if="isRfidAssgined" class="text-danger">Truck has already RFID assigned</div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeForm">Close</button>
            <button type="button" v-if="isRfidAssgined === false" :disabled="submitting" @click.prevent="assignRfid" class="btn btn-primary">Save changes</button>
            <button type="button" v-else disabled class="btn btn-secondary">Save changes</button>
        </div>
        </div>
    </div>
    </div>
</template>
<script>
export default {

    name: 'assign',

    props: ['showModal','truck'],

    data() {
        return {
            errors: [],
            toSubmit: {
                card_list: ''
            },
            rfids: [],
            loading: false,
            submitting: false
        }
    },

    watch: {
        showModal() {
            if(this.showModal == true) {
                $('#assignRFID').modal('show')
            }
        }
    },

    mounted() {
        this.getTruckRfid()
    },

    computed: {
        isRfidAssgined() {
            let fromCardholder = this.truck.driver ? this.truck.driver.map(item => item) : 0;
            return this.truck.card_id ? true :
            fromCardholder.length > 0 ? true : false;
        }
    },

    methods: {

        resetFields() {
            this.toSubmit = {
                card_list: ''
            }
        },

        getTruckRfid() {
            axios.get('/driver_rfid/public/truck-rfid')
            .then(response => {
                this.rfids = response.data
            })
        },

        assignRfid() {
            this.submitting = true
            axios.post(`/driver_rfid/public/assign-rfid/${this.truck.id}`,{
                card_list: this.toSubmit.card_list
            })
            .then(response => {
                if(response.status === 200) {
                    this.$emit('response',response.data)
                    return response.data
                }
            })
            .then(response => {
                this.resetFields()
                this.closeForm()
                this.submitting = false
            })
            .catch(error => {
                if(error.response.status == 422) {
                    this.errors = error.response.data
                    this.submitting = false
                }
            })
        },

        closeForm() {
            this.errors = []
            this.$emit('closeShowModal',false)
            $('#assignRFID').modal('hide')
            this.loading = false
        }
    }
}
</script>

