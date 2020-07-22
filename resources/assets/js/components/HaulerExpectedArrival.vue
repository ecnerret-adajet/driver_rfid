<template>
    <!-- Add New Pickup Modal -->
    <div class="modal fade" id="addTruckEta" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="queueter">
        <div class="modal-content">
        <div class="modal-header">

            <h6 class="modal-title" id="driverModalLabel">Add Truck ETA</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>


        </div>
        <div class="modal-body">

            <div class="form-group row" :class="{ ' has-danger' : errors.truck_id }">
                <label for="titleField" class="col-lg-4 col-form-label text-right">Plate Number</label>
                <div class="col-lg-8">
                <!-- <input type="text" class="form-control form-control-user" id="titleField" v-model="selectedCategories" placeholder="Select Categories"> -->
                <Select2 v-model="eta.truck_id"
                        style="background-color: #ffffff;"
                        :required="true"
                        :error="errors.truck_id"
                        :settings="settings"
                        :options="trucks"/>
                <div v-if="errors.truck_id" class="small mt-1 text-danger">{{ errors.truck_id[0] }}</div>
                </div>
            </div>

            <div class="form-group row" :class="{ ' has-danger' : errors.expected_arrival_date }">
                <label for="titleField" class=" col-lg-4 col-form-label text-right">Expected Arrival Date</label>
                <div class="col-lg-8">
                <input type="date"
                        :min="currentDate"
                        class="form-control form-control-user"
                        :class="{ 'is-invalid' : errors.expected_arrival_date }"
                        id="titleField" v-model="eta.expected_arrival_date"
                        placeholder="Enter Name">
                 <small class="form-text text-muted">Example: 01/20/2020</small>
                <div v-if="errors.expected_arrival_date" class="invalid-feedback">{{ errors.expected_arrival_date[0] }}</div>
                </div>
            </div>

            <div class="form-group row" :class="{ ' has-danger' : errors.expected_arrival_time }">
                <label for="titleField" class=" col-lg-4 col-form-label text-right">Expected Arrival Time</label>
                <div class="col-lg-8">
                <input type="time"
                        class="form-control form-control-user"
                        :class="{ 'is-invalid' : errors.expected_arrival_time }"
                        id="titleField" v-model="eta.expected_arrival_time"
                        placeholder="Enter Name">
                <small class="form-text text-muted">Example: 12:34PM or 08:20:AM</small>
                <div v-if="errors.expected_arrival_time" class="invalid-feedback">{{ errors.expected_arrival_time[0] }}</div>
                </div>
            </div>

             <div class="form-group row" :class="{ ' has-danger' : errors.remarks }">
                <label for="titleField" class=" col-lg-4 col-form-label text-right">Remarks</label>
                <div class="col-lg-8">
                <input type="text"
                        class="form-control form-control-user"
                        :class="{ 'is-invalid' : errors.remarks }"
                        id="titleField" v-model="eta.remarks"
                        placeholder="Enter Name">
                <div v-if="errors.remarks" class="invalid-feedback">{{ errors.remarks[0] }}</div>
                </div>
            </div>


        </div>

        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeDialog()">Cancel</button>
                 <button type="submit" class="btn btn-primary" @click="store()">Confirm</button>
        </div>

        </div>
    </div>
    </div><!-- end modal -->
</template>
<script>
import Select2 from 'v-select2-component';
import 'select2/dist/css/select2.min.css'
import '@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css'

export default {

    components: {
        Select2:Select2,
    },

    props: {
        user: String,
        showModal: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            loading: false,
            settings: {
                multiple: false,
                width: 'wide',
                theme: 'bootstrap4',
                placeholder: 'Select From Dropdown',
            },
            trucks: [],
            eta: {
                expected_arrival_date: new Date(),
                expected_arrival_time: '',
                remarks: '',
                truck_id: '',
            },
            errors: {}
        }
    },

    mounted() {
        this.getTruck()
    },

    watch: {
        showModal(){
            if(this.showModal ===  true) {
                $("#addTruckEta").modal({
                    backdrop: "static",
                    keyboard: false
                });
            } else {
                $("#addTruckEta").modal("hide");
            }
        }
    },

    computed: {
        currentDate() {
            let current_datetime = new Date()
            let getMonth = current_datetime.getMonth() + 1;
            let formatted_date = current_datetime.getFullYear() + "-" + ( getMonth > 9 ? getMonth : '0' + getMonth )  + "-" + current_datetime.getDate()
            console.log('check date format: ', formatted_date);
            return formatted_date;
        }
    },

    methods: {

        closeDialog() {
            $("#addTruckEta").modal("hide");
            this.eta = {
                expected_arrival_date: '',
                expected_arrival_time: '',
                remarks: '',
                truck_id: '',
            }
            this.$emit('modalCallback', false);
            this.$emit('proceedAction', false);
        },

         getTruck() {
            this.loading = true
            axios.get('/users/truck/hauler/' + this.user)
            .then(response => {
                const filtered = response.data.map(({ id, plate_number }) => ({ id: id, text: plate_number}))
                return Promise.resolve(filtered)
                .then(result => {
                    this.trucks = result
                    this.loading = false
                    return result
                })
                .then(() => {
                    this.eta = {
                        expected_arrival: new Date(),
                        remarks: '',
                        truck_id: '',
                    }
                })
            })
        },

        store() {
            console.log('store test')
            axios.post('/api/haulers-arrivals', {
                expected_arrival_date: this.eta.expected_arrival_date,
                expected_arrival_time: this.eta.expected_arrival_time,
                truck_id: this.eta.truck_id,
                remarks: this.eta.remarks
            })
            .then(response => {
                this.$emit('store',response.data)
                this.closeDialog()
                return response.data
            })
            .then(() => {
                this.eta = {
                    expected_arrival: new Date(),
                    remarks: '',
                    truck_id: '',
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
