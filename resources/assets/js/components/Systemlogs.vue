<template>
    <div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group {{ $errors->has('validity_start_date') ? ' has-danger' : '' }}">
                    <label>Start Validity Date</label>
                    <input type="date" class="form-control" v-model="searchDate" placeholder="Enter Date">
                </div>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-primary btn-block" href="#">Export Logs</a>
            </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
            <div v-if="!loading">
                <ul class="list-group">
                    <li v-for="activities in filteredActivities" class="list-group-item">
                        <div class="row">   
                            <div class="col-sm-1">

                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-user-o fa-stack-1x fa-inverse"></i>
                                    </span>

                            </div>
                            <div class="col-sm-8">
                                
                                

                            </div>
                            <div class="col-sm-3 pull-right right">
                               

                                
                            </div>
                        </div>
                    </li>
                    <li v-if="filteredActivities.length == 0"  class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 center">
                                <span>NO RECORD FOUND</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
                <div class="center-align" style="padding-top: 50px" v-if="loading">
                <div class="loader center">Loading...</div>
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
            export_link: '',
            searchDate: '',
            loading: false,
            activities: [],
        }
    },

    created() {
        this.getActivies()
    },

    methods: {
        getActivies() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/activities')
            .then(response => {
                this.activities = response.data
                this.loading = false
            });
        }
    },

    computed: {
        filteredActivities() {
            var activities_array = this.activities;
            var searchDate = moment(this.searchDate).format('YYYY-MM-DD');

            if(!searchDate){
                return activities_array;
            }

            searchDate = searchDate.trim().toLowerCase();

            activities_array = activities_array.filter(function(item){
                if(item.created_at.indexOf(searchDate) !== -1){
                    return item;
                }
            })

            return activities_array;

        }
    }



}
</script>