<template>
<div>

    <div class="card mx-auto mb-3">
        <div class="card-header">
       Entries Report

            
        </div>
        <div class="card-body">


<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Daily</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Weekly</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Montly</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                  <div class="row">
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL TRUCK ENTRIES</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ daily.trucks }}
                                    </p>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL PICKUP ENTRIES</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                         {{ daily.pickups }}
                                    </p>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL REASSIGNS</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ daily.reassigns }}
                                    </p>
                        </div>
                    </div><!-- end row -->
      


  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

           <div class="row">
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL TRUCK ENTRIES</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ weekly.trucks }}
                                    </p>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL PICKUP ENTRIES</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                         {{ weekly.pickups }}
                                    </p>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL REASSIGNS</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ weekly.reassigns }}
                                    </p>
                        </div>
                    </div><!-- end row -->

  </div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

             <div class="row">
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL TRUCK ENTRIES</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ monthly.trucks }}
                                    </p>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL PICKUP ENTRIES</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                         {{ monthly.pickups }}
                                    </p>
                        </div>
                        <div class="col-sm-4 text-center">
                                    <small class="text-muted">TOTAL REASSIGNS</small>
                                    <p class="card-text text-center" style="font-size: 80px;">
                                        {{ monthly.reassigns }}
                                    </p>
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
            axios.get('/driver_rfid/public/dailyEntries')
            .then(response => this.daily = response.data);
        },

        getWeekly() {
            axios.get('/driver_rfid/public/weeklyEntries')
            .then(response => this.weekly = response.data);
        },

        getMonthly() {
            axios.get('/driver_rfid/public/monthlyEntries')
            .then(response => this.monthly = response.data);
        }
    }
}
</script>