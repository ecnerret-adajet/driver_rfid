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
                 Queue Trucks
                </div>
                <div v-if="!loading">
                    <h3>
                        {{ lineups.length }}
                    </h3>
                </div>
                 <div v-if="loading">
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
          <div class="col-xl-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  Hustling Truck(s)
                </div>
               <div v-if="!loading">
                    <h3>
                    {{ marked }}

                    </h3>
                </div>
                 <div v-if="loading">
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
        </div><!-- end row -->

</div>
</template>
<script>
import moment from 'moment';
export default {
    data(){
        return {
            loading: false,
            lineups: [],
            marked: [],
            csrf: '',
        }
    },

    mounted() {
        this.csrf = window.Laravel.csrfToken;
    },

    created() {
        this.getLineups()
        this.getMarked()
    },

    methods: {
        getLineups() {
            this.loading = true
            axios.get('/driver_rfid/public/lineupJson')
            .then(response => {
                this.lineups = response.data
                this.loading = false
            });
        },

        getMarked() {
            this.loading = true
            axios.get('/driver_rfid/public/markedJson')
            .then(response => {
                this.marked = response.data
                this.loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        },
    }
}
</script>