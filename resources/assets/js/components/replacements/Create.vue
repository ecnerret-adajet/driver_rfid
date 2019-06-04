<template>
        <div class="modal fade" id="newReplacement" tabindex="-1" role="dialog" aria-labelledby="newReplacementLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newReplacementLabel">Add New Account</h5>
                <button type="button" class="close"  @click="closeForm" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group" :class="{ ' has-danger' : errors.driver_id }">
                    <label>Select Driver</label>
                    <select :class="{ 'is-invalid' : errors.driver_id }" class="form-control" v-model="toSubmit.driver_id">
                        <option value=""  selected>All Drivers</option>
                        <option v-for="(driver,i) in drivers" :key="i" selected :value="driver.id">{{ driver.name }}</option>
                    </select>
                    <div v-if="errors.driver_id" class="invalid-feedback">{{ errors.driver_id[0] }}</div>
                </div>

                <div class="form-group" :class="{ ' has-danger' : errors.card_id }">
                    <label>RFID Cards</label>
                    <select  :class="{ 'is-invalid' : errors.card_id }" class="form-control" v-model="toSubmit.card_id">
                        <option value="" selected>All RFID</option>
                        <option v-for="(card,c) in cards" :key="c" selected :value="card.CardID">{{ card.full_deploy }}</option>
                    </select>
                    <div v-if="errors.card_id" class="invalid-feedback">{{ errors.card_id[0] }}</div>
                </div>

                <div class="form-group" :class="{ ' has-danger' : errors.reason_replacement }">
                    <label>Reason Replacement</label>
                    <select  :class="{ 'is-invalid' : errors.reason_replacement }" class="form-control" v-model="toSubmit.reason_replacement">
                        <option value="" selected>Select Reason Replacement</option>
                        <option v-for="(reason,c) in reasons" :key="c" selected :value="reason">{{ reason }}</option>
                    </select>
                    <div v-if="errors.reason_replacement" class="invalid-feedback">{{ errors.reason_replacement[0] }}</div>
                </div>

                <div class="form-group" :class="{ ' has-danger' : errors.remarks }">
                    <label>Remarks</label>
                    <textarea class="form-control" :class="{ 'is-invalid' : errors.remarks }" v-model="toSubmit.remarks" rows="3"></textarea>
                    <div v-if="errors.remarks" class="invalid-feedback">{{ errors.remarks[0] }}</div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeForm">Cancel</button>
                <button  type="button" :disabled="loading" class="btn btn-primary" @click.prevent="addReplacement()">Submit</button>
            </div>
            </div>
        </div>
        </div>
</template>
<script>
export default {

    props: {
        showModal: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            errors: [],
            loading: false,
            cards: [],
            reasons: [],
            drivers: [],
            toSubmit: {
                driver_id: '',
                card_id: '',
                reason_replacement: '',
                remarks: ''
            }
        }
    },

    mounted() {
        this.getCardlist()
        this.getDrivers()
        this.getReasonReplacements()
    },

    watch: {
        showModal() {
            if(this.showModal == true) {
                $('#newReplacement').modal('show')
            }
        }
    },

    methods: {

        getDrivers() {
            axios.get('/driver_rfid/public/driversJson')
            .then(response => {
                this.drivers = response.data
            })
        },

        getCardlist() {
            axios.get('/driver_rfid/public/api-driver-rfid')
            .then(response => {
                this.cards = response.data
            });
        },

        getReasonReplacements() {
            axios.get('/driver_rfid/public/api-reason-replacement')
            .then(response => {
                this.reasons = response.data
            })
        },

        resetFields() {
            this.toSubmit = {
                driver_id: '',
                card_id: '',
                reason_replacement: '',
                remarks: ''
            }
        },

        addReplacement() {
            this.loading = true
            axios.post('/driver_rfid/public/api-replacements', {
                driver_id: this.toSubmit.driver_id,
                card_id: this.toSubmit.card_id,
                reason_replacement: this.toSubmit.reason_replacement,
                remarks: this.toSubmit.remarks
            })
            .then(response => {
                if(response.status === 200) { // if has a resource
                    // this.banks.unshift(response.data)
                    this.$emit('storeResponse', response.data)
                    return response.data
                }
            })
            .then(response => {
                this.returnMessage("Added successfully!")
                this.resetFields()
                this.closeForm()
            })
            .catch(error => {
                this.errors = error.response.data
                this.loading = false
            });
        },

        closeForm() {
            this.errors = []
            this.$emit('returnShowModal',false)
            $('#newReplacement').modal('hide')
            this.loading = false
        }

    }


}
</script>

