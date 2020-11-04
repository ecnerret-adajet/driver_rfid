<template>
        <div class="modal fade bs-assignCard-modal-lg" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Assign Card</h6>
                    <button type="button" class="close" @click="closeModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Driver Name</label>
                        <input type="text" class="form-control" id="name" v-model="pickup.driver_name" disabled>
                    </div>

                    <div class="form-group">
                        <label>Plate Number</label>
                        <input type="text" class="form-control" id="name" v-model="pickup.plate_number" disabled>
                    </div>

                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="form-control" id="name" v-model="pickup.company" disabled>
                    </div>

                    <div v-if="pickup.user.bypass_rfid === '0' || pickup.user.bypass_rfid === null">

                    <div class="form-group row" :class="{ ' has-danger' : errors.cardholder_list }">
                        <label class="col-md-4">Available Cards</label>
                        <div class="col-md-8">
                        <select class="form-control" style="width: 100%" v-model="selectedCardholder">
                          <option selected value="">All Cardholders...</option>
                          <option v-for="(cardholder,c) in cardholders" :key="c" :value="cardholder.CardholderID">{{cardholder.Name}}</option>
                        </select>
                        <div v-if="errors.cardholder_list" class="invalid-feedback">{{ errors.cardholder_list[0] }}</div>
                       </div>
                    </div>
                    </div>

                    <div v-else>

                        <span class="text-danger">
                            <i>
                            RFID is not required for this user / plant
                            </i>
                        </span>

                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                    <button type="submit" class="btn btn-primary" :disabled="submitting" @click="assignCard" >Confirm</button>
                </div>
                </div>
            </div>
        </div>
</template>

<script>


import VueToastr from '@deveodk/vue-toastr'
import '@deveodk/vue-toastr/dist/@deveodk/vue-toastr.css'


Vue.use(VueToastr, {
    defaultPosition: 'toast-top-right',
    defaultType: 'info',
    defaultTimeout: 1000
})

export default {

    props: {
        pickup: Object,
        showModal: {
            type: Boolean,
            default: false
        }
    },

    watch: {
        showModal() {
            if(this.showModal) {
                $('.bs-assignCard-modal-lg').modal('show')
            }
        }
    },

    mounted() {
        this.getCardholderAvailability()
    },

    data() {
        return {
            errors: [],
            cardholders: [],
            selectedCardholder: '',
            loading: false,
            submitting: false,
        }
    },

    created() {
        $(document).ready(function() {
            $('#select2-cardholder').select2({
            placeholder: "Select Cardholder",
            allowClear: true,
            });
        });
    },

    methods: {
        getCardholderAvailability() {
            axios.get('/driver_rfid/public/api/pickups-available')
            .then(response => {
                this.cardholders = response.data
            })
        },

        closeModal() {
            this.$emit('returnShowModal', false)
            $('.bs-assignCard-modal-lg').modal('hide')
        },

        assignCard() {
            console.log('check selected cardholder: ', this.selectedCardholder)
            this.submitting = true;
            axios.patch(`/driver_rfid/public/api/pickups-assign-card/${this.pickup.id}`,{
                cardholder_list: this.selectedCardholder
            })
            .then(response => {
                if(response.status === 200) {
                    this.$emit('response', response.data)
                    this.submitting = false;
                    this.closeModal();
                    return this.$toastr('success', 'Card is successfully assigned', 'Saved')
                }
            })
            .catch(error => {
                console.log('error: ', error)
                this.submitting = false;
                this.closeModal();
                return this.$toastr('error', 'Something went wrong, please try again', 'Opps')
            })
        },

        myChangeEventSignatory1(val){
            // console.log('selected value: ', this.signatory1);
            // console.log('selected value: ', val);
        },

         mySelectEventSignatory1({id, text}) {
            console.log({id, text})
         }
    }

}
</script>
