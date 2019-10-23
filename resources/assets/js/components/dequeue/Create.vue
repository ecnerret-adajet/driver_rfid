<template>
        <div class="modal fade" id="confirm-dequeue" tabindex="-1" role="dialog" aria-labelledby="confirm-dequeue-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-dequeue-label">Dequeue a entry</h5>
                <button type="button" class="close" @click="closeForm" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group" :class="{ ' has-danger' : errors.remarks }">
                    <label>Reason for dequeue</label>
                    <textarea :disabled="submitting" class="form-control" :class="{ 'is-invalid' : errors.remarks }" v-model="dequeue.remarks" rows="3"></textarea>
                    <div v-if="errors.remarks" class="invalid-feedback">{{ errors.remarks[0] }}</div>
                    <small id="emailHelp" class="form-text text-danger">By clicking the submit button. you are confirming to proceed this action.</small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeForm">Cancel</button>
                <button  type="button" v-if="!submitting" :disabled="submitting" class="btn btn-primary" @click.prevent="submitDequeue()">Submit</button>
                <button  type="button" v-else class="btn btn-primary" disabled>
                    <div id="wave" class="text-center ml-2 mr-2">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </button>
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
        },
        queue_entry_id: {
            type: Number,
            default: 0
        }
    },

    data() {
        return {
            errors: {},
            submitting: false,
            dequeue: {
                remarks: '',
            }
        }
    },

    watch: {
        showModal(){
            if(this.showModal == true) {
                $('#confirm-dequeue').modal('show')
            }
        }
    },

    methods: {

        resetFields() {
            this.dequeue = {
                remarks: '',
            }
        },

        submitDequeue() {
            this.submitting = true;
            axios.post('/api/dequeues', {
                queue_entry_id: this.queue_entry_id,
                remarks: this.dequeue.remarks
            })
            .then(response => {
                console.log('check response: ', response.status)
                if(response.status === 200) {
                    this.$emit('storeDequeue', {
                        queue_entry_id: this.queue_entry_id,
                        dequeue: response.data
                    })
                }
            })
            .then(response => {
                this.closeForm()
                this.submitting = false
            })
            .catch(error => {
                if(error.response.status == 422) {
                    this.errors = error.response.data
                    this.submitting = false
                }
            });
        },

        closeForm() {
            this.resetFields()
            this.errors = {}
            this.$emit('closeModal',false)
            $('#confirm-dequeue').modal('hide')
            this.submitting = false
        }


    }
}
</script>