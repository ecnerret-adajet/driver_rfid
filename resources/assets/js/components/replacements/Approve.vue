<template>
     <div class="modal fade" id="approveReplacement" tabindex="-1" role="dialog" aria-labelledby="approveReplacementLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveReplacementLabel">Approve a replacement</h5>
                <button type="button" class="close" @click="closeModal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Please confirm to proceed
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                <button type="button" class="btn btn-primary" :disabled="subitting"  @click.prevent="approveReplacement">Submit</button>
            </div>
            </div>
        </div>
        </div>
</template>
<script>
export default {

    props: ['approveData','showModal'],

    watch: {
        showModal() {
            if(this.showModal == true) {
                $('#approveReplacement').modal('show')
            }
        }
    },

    data() {
        return {
            subitting: false
        }
    },

    methods: {

        // returnMessage(message) {
        //     Vue.toasted.show(message, {
        //         theme: "primary",
        //         position: "bottom-right",
        //         duration : 5000
        //     });
        // },

        closeModal() {
            $('#approveReplacement').modal('hide')
            return this.$emit('returnshowModal',false)
        },


        approveReplacement() {
            this.subitting = true;
            axios.patch(`/driver_rfid/public/api-replacements-approve/${this.approveData.id}`,{
                status: true
            })
            .then(response => {
                if(response.status === 200) {
                    this.$emit('response', response.data)
                    return response.data
                }
            })
            .then(response => {
                this.subitting = false
                this.$emit('returnshowModal', false)
                $('#approveReplacement').modal('hide')
            })
            .catch(error => {
                this.subitting = false
                // this.returnMessage("Something went wrong, please refresh and try again")
            })
        },
    }

}
</script>

