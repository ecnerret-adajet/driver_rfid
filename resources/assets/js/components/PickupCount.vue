<template>
    <div>

        <div class="row mt-1 mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <small class="text-uppercase">NOT YET SERVED COUNT</small>
                    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="display-4"  v-if="!loading">
                            {{ totalCount.notYetServed }} <br/>
                            </span> <br/>

                            <span class="display-4 center-align text-center" v-if="loading">
                            <svg class="spinner mx-auto" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>
                            <br/>
                            </span>

                            <small class="text-uppercase">CREATED BY TEAM</small>
                        </div>
                        <div class="col">
                            <span class="display-4"  v-if="!loading">
                            {{ totalCount.mineNotServed }} <br/>
                            </span> <br/>

                            <span class="display-4 center-align text-center" v-if="loading">
                            <svg class="spinner mx-auto" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>
                            <br/>
                            </span>

                            <small class="text-uppercase">CREATED BY ME</small>
                        </div>
                    </div>
                </div>
                </div>
            </div>

             <div class="col">
                <div class="card">
                    <div class="card-header">
                        <small class="text-uppercase">SERVED COUNT</small>
                    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span class="display-4"  v-if="!loading">
                            {{ totalCount.served }} <br/>
                            </span> <br/>

                            <span class="display-4 center-align text-center" v-if="loading">
                            <svg class="spinner mx-auto" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>
                            <br/>
                            </span> 

                            <small class="text-uppercase">TEAM PICKUP SERVED</small>
                        </div>
                        <div class="col">
                            <span class="display-4"  v-if="!loading">
                            {{ totalCount.mineServed }} <br/>
                            </span> <br/>
                            
                            <span class="display-4 center-align text-center" v-if="loading">
                            <svg class="spinner mx-auto" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                            </svg>
                            <br/>
                            </span>

                            <small class="text-uppercase">MY PICKUP</small>
                        </div>
                    </div>
                </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script>
 export default {
     data() {
         return {
             loading: false,
             totalCount: []
         }
     },

    created() {
        this.getTotalCount()
    },

    methods: {
        getTotalCount() {
            this.loading = true
            axios.get('/driver_rfid/public/pickupCount')
            .then(response => {
                this.totalCount = response.data
                this.loading = false
            });
        }
    }
 
 }
</script>