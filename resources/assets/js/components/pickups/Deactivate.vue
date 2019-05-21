<template>
        <div class="modal fade bs-setInactive-modal-lg" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{ this.title }}</h6>
                    <button type="button" class="close" @click="closeModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ this.message }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                    <button type="submit" :disabled="submitting" class="btn btn-primary" @click="deactivate" >Confirm</button>
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
        pathUrl: String,
        data: Object,
        title: String,
        message: String,
        showModal: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            submitting: false,
        }
    },

    watch: {
        showModal() {
            if(this.showModal) {
                $('.bs-setInactive-modal-lg').modal('show')
            }
        }
    },

    methods: {

        closeModal() {
            this.$emit('returnShowModal', false)
            $('.bs-setInactive-modal-lg').modal('hide')
        },

        confirmModal() {
            console.log('confirm modal')
            this.closeModal()
        },

        deactivate() {
            this.submitting = true
            axios.patch(`${this.pathUrl}/${this.data.id}`)
            .then(response  => {
                console.log('response :', response)
                if(response.status === 200) {
                    return Promise.resolve(response.data)
                    .then(response => {
                        console.log('deactivae')
                        this.$emit('response', {
                            status: 'patch',
                            data: response
                        })
                        this.submitting = false
                        this.closeModal()
                        return this.$toastr('success', 'Card is successfully deactivated', 'Saved')
                    })
                }
            })
            .catch(error => {
                this.submitting = false
                this.closeModal();
                return this.$toastr('error', 'Something went wrong, please try again', 'Opps')
                console.log('error: ', error)
            })
        }
    }



}
</script>
