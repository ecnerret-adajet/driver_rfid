<template>
    <div>

        <div class="row">
            <div class="col">
                 <div class="form-group">
                <label class=" control-label">Location</label>
                <select class="form-control" v-model="location">
                    <option location value="1">Manila</option>
                    <option value="2">Lapaz (MNL)</option>
                    <option value="3">Bataan</option>
                </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label class=" control-label">Date</label>
                    <div class="input-group input-append date" id="datePicker">
                        <input type="date" class="form-control" v-model="date" />
                        <span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 mt-3">
            <div class="col">
                 <div class="form-group">
                     <a class="btn btn-primary btn-block" :href="'/exportEntries/' + location + '/' + date">Download Report</a>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import moment from 'moment';
export default {

    data() {
        return {
            location: 1,
            date: moment(new Date()).format('YYYY-MM-DD'),
        }
    },

    methods: {
        searchDate(date) {
            return moment(date).format('YYYY-MM-DD');
        }
    }
    
    
}
</script>
