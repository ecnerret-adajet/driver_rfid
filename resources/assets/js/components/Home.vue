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
                <div v-if="!is_loading">
                    <h3>
                 {{ home.trucks }}
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
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  All Drivers
                </div>
               <div v-if="!is_loading">
                    <h3>
                      {{ home.drivers }}
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
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                   For Printing
                </div>
                 <div v-if="!is_loading">
                    <h3>
                   {{ home.prints }}
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
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                   Users
                </div>
                <div v-if="!is_loading">
                    <h3>
                    {{ home.users }}
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
export default {
    data() {
        return {
            print_link: '/prints',
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
            axios.get('/homeJson')
            .then(response => {
                this.home = response.data
                this.is_loading = false
            });
        }
    }
}
</script>