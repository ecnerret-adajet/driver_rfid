<template>
    <div>
        <ul class="collapsible" data-collapsible="accordion">
            <li>

                <div class="collapsible-header">
                    
                    <div class="row" v-for="today in today_log">
                        <div class="col s2">
                            <span class="btn">
                                {{ moment(today.LocalTime) }}
                            </span>
                        </div>
                        <div class="col s2" v-for="driver in today.drivers">
                            <div v-for="truck in driver.trucks">
                                {{truck.plate_number}}
                            </div>
                        </div>
                        <div class="col s2" v-for="customer in today.customers">
                            {{ customer.address }}
                        </div>
                        <div class="col s2" v-for="(current_in, index) in filteredIn(today.CardholderID)">
                            <div v-if="index == 0">
                                {{ moment(current_in.LocalTime) }}
                            </div>
                        </div>
                        <div class="col s2" v-for="(current_out, index) in filteredOut(today.CardholderID)">
                            <div v-if="index == 0">
                                {{ moment(current_out.LocalTime) }}
                            </div>
                        </div>
                        <div class="col s2">
                            <div v-for="(current_out, x) in filteredOut(today.CardholderID)">
                                <div v-if="x == 0">
                                    <div v-for="(current_in, y) in filteredIn(today.CardholderID)">
                                        <div v-if="y == 0">
                                            {{ dateDiff(current_in.LocalTime, current_out.LocalTime) }}
                                        </div>
                                        <div v-else>
                                            NO IN
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    NO OUT
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="collapsible-body">

                </div>

            </li>
        </ul>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data() {
        return {
            search: '',
            loading: false,
            logs: [],
            all_in: [],
            all_out: [],
            match: []
        }
    },

    created() {
        this.allIn()
        this.allOut()
        this.logs()
        this.match()
    },

    methods: {
        allIn() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/entriesIn')
            .then(response => {
                this.all_in = response.data
                this.loading = false
            });
        },

        allOut(){
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/entriesOut')
            .then(response => {
                this.all_out = resonpose.data
                this.loading = false
            });
        },

        logs(){
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/logs')
            .then(response => {
                this.logs = response.data
                this.loading = false
            });
        }
    },

    computed: {
        filteredIn(today) {
            var in_array = this.all_in;
            var cardholder_id = today;
            
            in_array = in_array.filter(function(item) {
              return  item.CardholderID.indexOf(cardholder_id) > -1
            })

            return in_array;

        },

        filteredOut(today) {
            var out_array = this.all_out;
            var cardholder_id = today;

            out_array = out_array.filter(function(item) {
                return item.CardholderID.indexOf(cardholder_id) > -1
            })

            return out_array;
        },

        filteredMatch(id) {
            var logs = this.logs;
            var current_1 = id;
            var current_2 = id - 5;

            logs = logs.filter(function(item) {
                if(item.LogID < current_1 && item.LogID >= current_2) {
                    return item;
                }
            })

            return logs;

        },

        moment(date) {
            return moment(date).format('MMMM  d, Y h:m:s A');
        },

        dateDiff(now, then) {
           return  moment.utc(this.moment(now,"DD/MM/YYYY HH:mm:ss").diff(this.moment(then,"DD/MM/YYYY HH:mm:ss"))).format("HH:mm:ss")
        }
    }

}
</script>