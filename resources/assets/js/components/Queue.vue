<template>
<div>


 <!-- Icon Cards -->
        <div class="row">
          <div class="col-xl-6 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                 Queue Count
                </div>
                <div v-if="!is_loading">
                    <h3>
                0
                    </h3>
                </div>
                 <div v-if="is_loading">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                        <svg class="spinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </div>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-left">View Details</span>
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  Marked as done
                </div>
               <div v-if="!is_loading">
                    <h3>
                      0

                    </h3>
                </div>
                 <div v-if="is_loading">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                        <svg class="spinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                        </svg>	
                    </div>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-left">View Details</span>
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
        </div><!-- end row -->

<!-- 
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
                                            <a :href="queues_link + queue.LogID" class="btn btn-primary pull-right btn-sm">Mark as done</a>                                            
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
                </div> -->




</div>
</template>
<script>
import moment from 'moment';
export default {
    data(){
        return {
            searchString: '',
            is_loading: false,
            loading: false,
            queues_link: '/driver_rfid/public/queues/',
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