<template>
  <div>
        <div class="form-row mb-2 mt-3">
                     
                <div class="col-6">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Search</label>
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search LogID or by date format YYYY-MM-DD" />
                    </div>
                </div>
                
                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Filter by date</label>
                        <input class="form-control" type="date" v-model="date" :disabled="selected">
                    </div>
                </div>

             

                <div class="col-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="row">
                            <div class="col text-right">
                                <button class="btn btn-block" :class="{'btn-primary' : date, 'btn-danger not-allowed' : !date }" :disabled="!date" @click="selected = true" v-if="!selected">Generate</button>
                                <button class="btn btn-block btn-danger" v-if="selected"  @click="backToLatest()"><i class="fa fa-arrow-left"></i> Back to latest</button>
                            </div>
                        </div>
                    </div>
                </div>

        </div> <!-- end row -->

            <app-driver-logs-search   v-if="selected"
                                :driver="driver_id"
                                :search="searchString"
                                :date="searchDate(date)">
            </app-driver-logs-search>

            <app-driver-logs v-if="!selected"
                                    :driver="driver_id"
                                    :search="searchString">
            </app-driver-logs>

       
    </div><!-- end template -->

</template>
<script>
    import moment from 'moment';
    import DriverLogs from './DriverLogs.vue';
    import DriverLogsSeaerch from './DriverLogsSearch.vue';

    export default {

        props: ['driver_id'],

        components: {
            appDriverLogs : DriverLogs,
            appDriverLogsSearch : DriverLogsSeaerch,
        },

        data() {
            return {
                selected: false,
                isSearching: false,
                searchString: '',
                date: '',
            }
        },

        methods: {
            backToLatest() {
                this.selected = false;
                this.date = null;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            }
        }

    }

</script>
<style>
    .disabled, .not-allowed {
        cursor: not-allowed ! important;
    }
    button {
        cursor: pointer;
    }
</style>