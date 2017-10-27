<template>
<div>

    <div class="card mx-auto mb-3">
        <div class="card-header">
       Entries Report

            <ul class="nav pull-right btn-group" id="pills-tab" role="tablist">
                    <a class="btn btn-secondary btn-sm active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Daily</a>
                    <a class="btn btn-secondary btn-sm" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Weekly</a>
                    <a class="btn btn-secondary btn-sm" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Monthly</a>
            </ul>

  
        </div>
        <div class="card-body">



<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                  <div class="row">
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL TRUCK ENTRIES</small>
                                     <span v-if="!loading">
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ daily.trucks }}
                                    </p>
                                    </span>
                                     <span v-if="loading" class="mt-3">
                                        <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                            <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                            </svg>	
                                        </div>
                                    </span>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL PICKUP ENTRIES</small>
                                    <span v-if="!loading">
                                    <p class="card-text text-center" style="font-size: 80px;">
                                         {{ daily.pickups }}
                                    </p>
                                    </span>
                                   <span v-if="loading" class="mt-3">
                                        <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                            <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                            </svg>	
                                        </div>
                                    </span>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL REASSIGNS</small>
                                    <span v-if="!loading">
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ daily.reassigns }}
                                    </p>
                                    </span>
                                   <span v-if="loading" class="mt-3">
                                        <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                            <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                            </svg>	
                                        </div>
                                    </span>
                        </div>
                    </div><!-- end row -->
      


  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

        <div class="row">
            <div class="col-sm-4 text-center">
                        <small class="text-muted">TOTAL TRUCK ENTRIES</small>
                        <span v-if="!loading">
                            <p class="card-text text-center" style="font-size: 80px;">
                                {{ weekly.trucks }}
                            </p>
                        </span>
                       <span v-if="loading" class="mt-3">
                            <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                </svg>	
                            </div>
                        </span>
            </div>
            <div class="col-sm-4 text-center">
                        <small class="text-muted">TOTAL PICKUP ENTRIES</small>
                        <span v-if="!loading">
                            <p class="card-text text-center" style="font-size: 80px;">
                                    {{ weekly.pickups }}
                            </p>
                        </span>
                        <span v-if="loading" class="mt-3">
                            <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                </svg>	
                            </div>
                        </span>
            </div>
            <div class="col-sm-4 text-center">
                        <small class="text-muted">TOTAL REASSIGNS</small>
                        <span v-if="!loading">
                            <p class="card-text text-center" style="font-size: 80px;">
                                {{ weekly.reassigns }}
                            </p>
                        </span>
                         <span v-if="loading" class="mt-3">
                            <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                </svg>	
                            </div>
                        </span>
            </div>
        </div><!-- end row -->

  </div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

             <div class="row">
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL TRUCK ENTRIES</small>
                                    <span v-if="!loading">
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ monthly.trucks }}
                                    </p>
                                    </span>
                                      <span v-if="loading" class="mt-3">
                                            <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                                </svg>	
                                            </div>
                                        </span>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL PICKUP ENTRIES</small>
                                    <span v-if="!loading">
                                    <p class="card-text text-center" style="font-size: 80px;">
                                         {{ monthly.pickups }}
                                    </p>
                                    </span>
                                    <span v-if="loading" class="mt-3">
                                        <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                            <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                            </svg>	
                                        </div>
                                    </span>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL REASSIGNS</small>
                                     <span v-if="!loading">
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ monthly.reassigns }}
                                    </p>
                                    </span>
                                    <span v-if="loading" class="mt-3">
                                        <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                                            <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                                            </svg>	
                                        </div>
                                    </span>
                        </div>
                    </div><!-- end row -->


  </div>
</div>
            
        </div><!-- end card-body -->
    </div> <!-- end card -->

</div><!-- end wrapper -->
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            daily: [],
            weekly: [],
            monthly: []
        }
    },

    created() {
        this.getDaily()
        this.getWeekly()
        this.getMonthly()
    },

    methods: {
        getDaily() {
            this.loading = true
            axios.get('/driver_rfid/public/dailyEntries')
            .then(response => {
                this.daily = response.data
                this.loading = false
            });
        },

        getWeekly() {
            this.loading = true
            axios.get('/driver_rfid/public/weeklyEntries')
            .then(response => {
                this.weekly = response.data
                this.loading = false
            });
        },

        getMonthly() {
            this.loading = true
            axios.get('/driver_rfid/public/monthlyEntries')
            .then(response => {
                this.monthly = response.data
                this.loading = false
            });
        }
    }
}
</script>