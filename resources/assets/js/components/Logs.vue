<template>
    <div>
        <ul class="collapsible popout" data-collapsible="accordion">
            <li v-for="(today, index) in logs">
                 
                <div class="collapsible-header">

                          <div class="col s2" v-for="(cardholder, index) in today.cardholders">
                                <div v-for="(card,x) in cardholder.cards">
                                        <div v-for="(driver, y) in card.drivers">
                                                <div v-for="(truck, t) in driver.trucks">
                                                           {{truck.plate_number}}
                                                </div>
                                        </div>
                                </div>
                        </div>
    

                        <div class="col s2" v-for="(cardholder, index) in today.cardholders">
                                <div v-for="(card,x) in cardholder.cards">
                                        <div v-for="(driver, y) in card.drivers">
                                                    {{driver.name}}
                                        </div>
                                </div>
                        </div>

                        


                      
               
                        
                   

                </div>
                <div class="collapsible-body">
                    <span>lasjflasjflakjflajflas</span>
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
            in: [],
            out: [],
        }
    },

    created() {
        this.getLogs()
        this.getIn()
        this.getOut()
    },

    methods: {
        getLogs(){
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/logs')
            .then(response => {
                this.logs = response.data
                this.loading = false
            });
        },

        getIn() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/entriesIn')
            .then(response => {
                this.in = response.data
                this.loading = false
            });
        },

        getOut() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/entriesOut')
            .then(response => {
                this.out = response.data
                this.loading = false
            });
        },
        
        moment(date) {
            return moment(date).format('MMMM  d, Y h:m:s A');
        },

        dateDiff(now, then) {
           return  moment.utc(moment(now,"DD/MM/YYYY HH:mm:ss").diff(moment(then,"DD/MM/YYYY HH:mm:ss"))).format("HH:mm:ss");
        },

        dateDuration(date) {
            return moment(date, "YYYYMMDD").fromNow();
        }
    }, 

    computed: {
        filterIn(today) {
            var in_array = this.in;
            var search =  today.split(' ');

           in_array = in_array.filter(function(item){
                if(item.CardholderID.indexOf(search) !== -1){
                    return item;
                }
            })

            return in_array;
        }
    }

}
</script>