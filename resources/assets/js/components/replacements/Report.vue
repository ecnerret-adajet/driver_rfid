<template>
     <div class="modal fade" id="generateReport" tabindex="-1" role="dialog" aria-labelledby="generateReportLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateReportLabel">Export Summary Report</h5>
                <button type="button" class="close"  @click="closeForm" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group" :class="{ ' has-danger' : errors.date_from }">
                    <label>Date From</label>
                     <input  :disabled="submitting" class="form-control" :class="{ 'is-invalid' : errors.date_from }" type="date" v-model="toSubmit.date_from">
                    <div v-if="errors.date_from" class="invalid-feedback">{{ errors.date_from[0] }}</div>
                </div>

                <div class="form-group" :class="{ ' has-danger' : errors.date_to }">
                    <label>Date To</label>
                     <input  :disabled="submitting" class="form-control" :class="{ 'is-invalid' : errors.date_to }" type="date" v-model="toSubmit.date_to">
                    <div v-if="errors.date_to" class="invalid-feedback">{{ errors.date_to[0] }}</div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeForm">Cancel</button>
                <button type="button" :disabled="submitting" class="btn btn-primary" @click.prevent="generateReport()">Generate</button>
            </div>
            </div>
        </div>
        </div>
</template>
<script>
import moment from 'moment';
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
            submitting: false,
            toSubmit: {
                date_from: moment(new Date()).format('YYYY-MM-DD'),
                date_to: moment(new Date()).format('YYYY-MM-DD'),
            }
        }
    },

    watch: {
        showModal() {
            if(this.showModal == true) {
                $('#generateReport').modal('show')
            }
        }
    },

    methods: {

        resetFields() {
            return this.toSubmit = {
                date_from: moment(new Date()).format('YYYY-MM-DD'),
                date_to: moment(new Date()).format('YYYY-MM-DD'),
            }
        },

        generateReport() {
            this.submitting = true;
            let url = `/api-replacements-report/${this.toSubmit.date_from}/${this.toSubmit.date_to}`;
            return Promise.resolve(url)
            .then(download => {
                return window.location.href = download
            })
            .then(() => {
                this.closeForm()
                this.submitting = false
            })
        },

        closeForm() {
            this.resetFields()
            this.errors = []
            this.$emit('returnShowModal',false)
            $('#generateReport').modal('hide')
            this.submitting = false
        }
    }
}
</script>
