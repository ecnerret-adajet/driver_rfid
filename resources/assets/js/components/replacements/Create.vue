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

                <div class="form-group" :class="{ ' has-danger' : errors.bank_list }">
                    <label>Select Driver</label>
                    <select :class="{ 'is-invalid' : errors.bank_list }" class="form-control" v-model="toSubmit.bank_list">
                        <option value=""  selected>All Banks</option>
                        <option v-for="(bank,i) in banks" :key="i" selected :value="bank.id">{{ bank.name + " - " + bank.branch }}</option>
                    </select>
                    <div v-if="errors.bank_list" class="invalid-feedback">{{ errors.bank_list[0] }}</div>
                </div>

                <div class="form-group" :class="{ ' has-danger' : errors.company_list }">
                    <label>Company</label>
                    <select :class="{ 'is-invalid' : errors.company_list }" class="form-control" v-model="toSubmit.company_list">
                        <option value=""  selected>All Companies</option>
                        <option v-for="(company,c) in companies" :key="c" selected :value="company.id">{{ company.department ? company.department + " - " + company.name : company.name }}</option>
                    </select>
                    <div v-if="errors.company_list" class="invalid-feedback">{{ errors.company_list[0] }}</div>
                </div>

                <div class="form-group" :class="{ ' has-danger' : errors.account_number }">
                    <label>Account Number</label>
                    <input type="text" :class="{ 'is-invalid' : errors.account_number }" class="form-control" id="name" v-model="toSubmit.account_number" placeholder="Enter Account Number">
                    <div v-if="errors.account_number" class="invalid-feedback">{{ errors.account_number[0] }}</div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeForm">Cancel</button>
                <button v-if="isCreate === false" type="button" class="btn btn-primary" :disabled="loading" @click.prevent="updateAccount">Update</button>
                <button v-else type="button" :disabled="loading" class="btn btn-primary" @click.prevent="addReplacement">Submit</button>
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
    },

    watch: {
        showModal() {
            if(this.showModal == true) {
                $('#newReplacement').modal('show')
            }
        }
    },

    methods: {

        getCardlist() {
            axios.get('/driver_rfid/public/card-list')
            .then(response => {
                this.cards = response.data
            });
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
            axios.post('/api-replacement', {
                driver_id: this.toSubmit.driver_id,
                card_id: this.toSubmit.card_id,
                reason_replacement: this.toSubmit.reason_replacement,
                remarks: this.toSubmit.remarks
            })
            .then(response => {
                if(response.status === 201) { // if has a resource
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
                if(error.response.status == 422) {
                    this.errors = error.response.data
                    this.loading = false
                }
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

