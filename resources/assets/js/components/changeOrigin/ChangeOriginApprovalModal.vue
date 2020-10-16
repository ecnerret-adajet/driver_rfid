<template>
    <!-- Remove Driver Modal -->
    <div class="modal fade" id="changeOrignModal" tabindex="-1" role="dialog" aria-labelledby="changeOrignModal" aria-hidden="true">
    <div class="modal-dialog" id="queueter">
        <div class="modal-content">
        <div class="modal-header">

            <h6 class="modal-title" id="driverModalLabel">Change Origin Approval</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>


        </div>
        <div class="modal-body">

            <div class="form-group row" :class="{ ' has-danger' : errors.approval_type_id }">
                <label for="titleField" class="col-lg-4 col-form-label text-right">Plate Number</label>
                <div class="col-lg-8">

                <select class="form-control" v-model="approval.approval_type_id">
                    <option value="" selected>Select Status</option>
                    <option v-for="(atype,i) in approvalTypes" :key="i" selected :value="atype.id">{{ atype.name }}</option>
                </select>
                <!-- <input type="text" class="form-control form-control-user" id="titleField" v-model="selectedCategories" placeholder="Select Categories"> -->
                <div v-if="errors.approval_type_id" class="small mt-1 text-danger">{{ errors.approval_type_id[0] }}</div>
                </div>
            </div>

            <div class="form-group row" :class="{ ' has-danger' : errors.approval_remarks }">
                <label for="titleField" class=" col-lg-4 col-form-label text-right">Remarks</label>
                <div class="col-lg-8">
                <input type="text"
                        class="form-control form-control-user"
                        :class="{ 'is-invalid' : errors.approval_remarks }"
                        id="titleField" v-model="approval.approval_remarks"
                        placeholder="Enter Name">
                <div v-if="errors.approval_remarks" class="invalid-feedback">{{ errors.approval_remarks[0] }}</div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeDialog()">Cancel</button>
            <button type="submit" class="btn btn-primary" @click="update()">Confirm</button>
        </div>

        </div>
    </div>
    </div><!-- end modal -->
</template>
<script>
export default {

    props: {
        changeOrigin: {
            type: Object,
            default: {}
        },
        showModal: {
            type: Boolean,
            default: false,
        }
    },

    data() {
        return {
            errors: {},
            approvalTypes: [],
            approval: {
                approval_type_id: '',
                approver_remarks: ''
            }
        }
    },

    mounted() {
        this.getApprovalTypes()
    },

    watch: {
        showModal(){
            if(this.showModal ===  true) {
                $("#changeOrignModal").modal({
                    backdrop: "static",
                    keyboard: false
                });
            } else {
                $("#changeOrignModal").modal("hide");
            }
        }
    },

    methods: {
        closeDialog() {
            $("#changeOrignModal").modal("hide");
            this.approval = {
                approval_type_id: '',
                approver_remarks: ''
            }
            this.$emit('callbackUpdate', {})
            this.$emit('callbackShowModal', false)
        },

        getApprovalTypes() {
            axios.get('/driver_rfid/public/api/approval-types')
            .then(response => {
                console.log('check approval types: ', response);
                this.approvalTypes = response.data
            })
        },

        update() {
            console.log('update test')
            axios.patch(`/driver_rfid/public/api/change-origins/approval/${this.changeOrigin.id}`, {
                approval_type_id: this.approval.approval_type_id,
                approval_remarks: this.approval.approval_remarks,
            })
            .then(response => {
                console.log('check store data: ', response.status)
                if(response.status === 200) {
                    this.closeDialog()
                }
                return response.data
            })
            .then(() => {
                this.approval = {
                    approval_type_id: '',
                    approval_remarks: '',
                }
            })
            .catch(error => {
                console.log('check error: ', error.response.data)
                if(error.response.status === 422) {
                    this.errors = error.response.data
                }
            })
        }
    }


}
</script>
