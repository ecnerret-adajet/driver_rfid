<template>
    <div>

          <!-- Icon Cards -->
        <div class="row">
          <div class="col-xl-4 col-sm-4 mb-3">
            <div class="card text-dark bg-light o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                Added Pickups Today
                </div>
                <div v-if="!is_loading">
                    <h3>
                 {{ pickupValue.all_pickups }}
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
              <a href="#" class="card-footer text-dark clearfix small z-1">
              </a>
            </div>
          </div>
          <div class="col-xl-4 col-sm-4 mb-3">
            <div class="card text-dark bg-light o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                    Ongoing Pickup
                </div>
               <div v-if="!is_loading">
                    <h3>
                      {{ pickupValue.current_pickup }}
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
              <a href="#" class="card-footer text-dark clearfix small z-1">
              </a>
            </div>
          </div>
          <div class="col-xl-4 col-sm-4 mb-3">
            <div class="card text-dark bg-light o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                   Available Card
                </div>
                 <div v-if="!is_loading">
                    <h3>
                   {{ pickupValue.available_card - pickupValue.current_pickup }}
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
              </a>
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
            searchString: '',
            loading: false,
            is_loading: false,
            pickups: [],
            pickupValue: [],
            pickup_link: '/driver_rfid/public/pickups/',
        }
    },

    created() {
        this.getPickups()
        this.getPickupValue()
    },

    methods: {
        getPickups() {
            this.loading = true
            axios.get('/driver_rfid/public/pickupsJson')
            .then(response => {
                this.pickups = response.data
                this.loading = false
            });
        },

        getPickupValue()
        {
            this.is_loading = true
            axios.get('/driver_rfid/public/pickupsStatus')
            .then(response => {
                this.pickupValue = response.data
                this.is_loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM  D, Y h:m:s A');
        },

        checkDate(date) {
            return moment(date).format('MMMM  D, Y h:m:s A');
        },

        addHour(date) {
            return moment(date).add(1, 'hours').format('MMMM  D, Y h:m:s A');
        }
    },

}
</script>