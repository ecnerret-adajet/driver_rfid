<template>
  <div>

        <!-- Icon Cards -->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                 All Trucks
                </div>
                <div>
                    <h3>
                 {{ home.trucks }}
                    </h3>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-left">View Details</span>
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  All Drivers
                </div>
                <div>
                    <h3>
                      {{ home.drivers }}
                    </h3>
                </div>
            <div v-if="is_loading">
                    <div class="preloader-wrapper small active">
                        <div class="spinner-layer spinner-green-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
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
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                   For Printing
                </div>
                 <div>
                    <h3>
                   {{ home.prints }}
                    </h3>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-left">View Details</span>
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                   Users
                </div>
                <div>
                    <h3>
                    {{ home.users }}
                    </h3>
                </div>
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fa fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        </div>


 
  </div>
</template>

<script>
export default {
    data() {
        return {
            print_link: '/driver_rfid/public/prints',
            home: [],
            is_loading: false
        }
    },

    created() {
      this.getHome()
    },

    methods: {
        getHome() {
            this.is_loading = true
            axios.get('http://localhost/driver_rfid/public/homeJson')
            .then(response => {
                this.home = response.data
                this.is_loading = false
            });
        }
    }
}
</script>