<template>
  <div>
            <div clas="row mt-3">


        

                <div class="input-group mt-3 mb-3">
                <input type="text" class="form-control" placeholder="Search Entry" aria-describedby="basic-addon2" v-model="searchString">
                  <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-danger rounded-0">
                            <small class="text-uppercase text-white">
                                Served
                            </small>
                        </button>
                        <button type="button" class="btn btn-warning rounded-right">
                            <small class="text-uppercase text-white">
                                Unserved
                            </small>
                        </button>
                    </div>
                </div>


            </div> <!-- end row -->

                <div class="row">
                    <div class="col-sm-12">
                        <div v-if="!loading">
                            <ul class="list-group">
                                <li v-for="pickup in filteredPickups" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">

                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                            </span>
                                        
                                        </div>
                                        <div class="col-sm-5">
                                           <span> 
                                                    {{ pickup.plate_number }}
                                          </span> : 
                                                <small class="badge badge-primary mr-2" v-if="pickup.cardholder">
                                                        {{ pickup.cardholder.Name }}
                                                </small> 
                                                 <small class="badge badge-danger mr-2" v-if="!pickup.cardholder">
                                                        NOT YET SERVED
                                                </small> 
                                                <br/>
                                            
                                            <span class="text-muted">
                                               {{ pickup.driver_name }}
                                            </span>

                                            <br/>
                                            
                                            <span>
                                                 {{pickup.company}}
                                            </span>

                                            <br/>

                                            <small class="tex-muted text-uppercase">
                                                CREATED BY:
                                            </small>
                                            <br/>
                                            <small class="text-muted">
                                                {{ pickup.user.name }}
                                            </small>


                                        </div>

                                        <div class="col-sm-3">
                                           <span class="text-muted text-uppercase">
                                               Created Date
                                           </span><br/>
                                           <span>
                                                {{ moment(pickup.created_at) }}                                         
                                            </span><br/>
                                             <span class="text-muted text-uppercase">
                                               Plant Out Date
                                           </span><br/>
                                            <span v-if="pickup.deactivated_date">
                                                {{ moment(pickup.deactivated_date) }}
                                            </span>
                                            <span class="text-danger" v-else>
                                                NOT YET SERVED
                                            </span>
                                        
                                        </div>


                                        <div class="col-sm-3 pull-right right">
                                            <span class="text-muted text-uppercase">
                                               DO NUMBER:
                                           </span><br/>  
                                           <span>
                                                {{ pickup.do_number }}                                         
                                            </span><br/>
                                            <span v-if="pickup.deactivated_date">
                                                    {{ dateDiff(pickup.created_at, pickup.deactivated_date) }} hour(s)
                                            </span>
                                            <span v-else>
                                                NOT YET SERVED
                                            </span>
                                        </div>

                                        
                                    </div>
                                </li>
                                <li v-if="filteredPickups.length == 0"  class="list-group-item">
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
        data() {
            return {
                loading: false,
                pickups: [],
                searchString: '',
            }
        },

        created()
        {
            this.getPickups()
        },

        methods: {
            getPickups() {
                this.loading = true
                axios.get('/driver_rfid/public/monitor/pickups')
                .then(response => {
                    this.pickups = response.data
                    this.loading = false
                });
            },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },

            dateDiff(startTime, endTime) {
                    var a = moment(startTime);   
                    var b = moment(endTime);   
                    return b.diff(a, 'hours');
            },
        },

        computed: {
            filteredPickups() {
                var pickups_array = this.pickups;
                var searchString = this.searchString;

                if(!searchString) {
                    return pickups_array;
                }

                searchString = searchString.trim().toLowerCase();

                // hauler_name = item.hauler.map(a => a.name);
        
                pickups_array = pickups_array.filter(function(item){
                    if(item.plate_number.toLowerCase().indexOf(searchString) !== -1 ||
                       item.driver_name.toLowerCase().indexOf(searchString) !== -1 ||
                       item.company.toLowerCase().indexOf(searchString) !== -1 ){
                        return item;
                    }
                })

                return pickups_array;
            }
        }

    }
</script>