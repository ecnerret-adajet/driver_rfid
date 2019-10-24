<template>
        <div>
     

            <div class="form-group" :class="{ ' has-danger' : errors.remarks }">
                <label>Reason for dequeue</label>
                <textarea :disabled="submitting" class="form-control" :class="{ 'is-invalid' : errors.remarks }" v-model="dequeue.remarks" rows="3"></textarea>
                <div v-if="errors.remarks" class="invalid-feedback">{{ errors.remarks[0] }}</div>
                <small id="emailHelp" class="form-text text-danger">By clicking the submit button. you are confirming to proceed this action.</small>
            </div>

            <div class="row">
                <div class="col float-right">

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
                this.$emit('storeDequeue', {
                    queue_entry_id: this.queue_entry_id,
                    dequeue: response.data.result
                })
                window.location.href = response.data.redirect;
            })
            .then(response => {
                // this.closeForm()
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