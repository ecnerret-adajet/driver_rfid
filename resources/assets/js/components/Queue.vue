<template>
<div>


   <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="queue in queues" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1" v-for="driver in queue.drivers">
                                             <img :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </div>
                                        <div class="col-sm-5" v-for="driver in queue.drivers">
                                            <a :href="'/driver_rfid/public/drivers/' + driver.id">{{driver.name}}</a>
                                            <br/>
                                            <span v-for="truck in driver.trucks">
                                                {{ truck.plate_number }}
                                            </span>
                                            <br/>
                                            <span v-for="hauler in driver.haulers">
                                                {{hauler.name}}
                                            </span>
                                        </div>
                                        <div class="col-sm-4">
                                            <span>{{ moment(queue.LocalTime) }}</span>
                                        </div>
                                        <div class="col-sm-2 pull-right right">
                                            <a href="#" class="btn btn-primary pull-right btn-sm">Mark as done</a>                                            
                                        </div>
                                    </div>
                                </li>
                                <li v-if="queues.length == 0"  class="list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 center">
                                            <span>NO RECORD FOUND</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                         <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;" v-if="loading">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>	
                        </div>
                    </div>
                </div>




</div>
</template>
<script>
import moment from 'moment';
export default {
    data(){
        return {
            loading: false,
            avatar_link: 'http://localhost/driver_rfid/storage/app/',
            queues: []
        }
    },

    created() {
        this.getQueues()
    },

    methods: {
        getQueues() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/queueJson')
            .then(response => {
                this.queues = response.data
                this.loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        },
    }
}
</script>