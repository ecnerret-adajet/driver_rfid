<template>
    <div>

     <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6 text-white">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
            <span class="display-4" v-for="(trucks, t) in trucksInPlant.slice(0,1)" :key="t" v-if="!loading1">
                <h3>{{ trucks.total_count }}</h3>
            </span>

            <span v-if="loading1">
                <div id="wave" class="h4 mb-3 mt-2">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </span>

                <p>Current Trucks In Plant</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a v-if="!loading1" href="javascript:void(0)" data-toggle="modal" data-target="#totalTrucks" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              <a v-else class="small-box-footer disabled">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6 text-white">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
            <span v-for="(shipment, s) in assignedShipment.slice(0,1)" :key="s" v-if="!loading2">
                <h3>{{ shipment.total_assigned }} </h3>
            </span>
             <span v-if="loading2">
                <div id="wave" class="h4 mb-3 mt-2">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </span>

                <p>Shipment Assigned Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a v-if="!loading2" href="#" class="small-box-footer" data-toggle="modal" data-target="#totalAssigned">More info <i class="fa fa-arrow-circle-right"></i></a>
              <a v-else class="small-box-footer disabled">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6 text-white">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <span v-if="!loading3">
                    <h3>{{ totalPickup.served }}</h3>
                </span>
                 <span v-if="loading3">
                <div id="wave" class="h4 mb-3 mt-2">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </span>

                <p>Served Pickups Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="javascript:void(0)" class="small-box-footer"> <strong>{{ totalPickup.in_plant }}</strong> Current pickup in plant </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6 text-white">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <span class="display-3" v-if="!loading4">
                 <h3> {{ totalPrint.length }} </h3>
                </span>
                 <span v-if="loading4">
                <div id="wave" class="h4 mb-3 mt-2">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </span>
                <p>For Printing</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a  href="javascript:void(0)" v-if="!loading4" class="small-box-footer" data-toggle="modal" data-target="#forPrinting">More info <i class="fa fa-arrow-circle-right"></i></a>
              <a v-else class="small-box-footer disabled">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>


        <!-- For Printing Modal -->
        <div class="modal fade" id="forPrinting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">For Printing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <ul class="list-group list-group-flush">
                        <li v-for="(driver, p) in totalPrint.slice(0,5)" :key="p" class="list-group-item">
                            <div class="row">
                                <div class="col-2">
                                    <img v-if="driver.image" :src="'/storage/' + driver.image.avatar" class="rounded-circle mx-auto mt-2" style="height: 60px; width: auto;"  align="middle">
                                    <img v-else :src="'/storage/' + driver.avatar" class="rounded-circle mx-auto mt-2" style="height: 60px; width: auto;"  align="middle">
                                </div>
                                <div class="col-10">
                                    <p class="p-0 m-0">
                                        {{ driver.name }}
                                    </p>
                                    <p v-if="driver.truck.length != 0" class="p-0 m-0">
                                        {{ driver.truck[0].plate_number }}
                                    </p>
                                    <p class="text-danger p-0 m-0" v-else>
                                        NO PLATE NUMBER
                                    </p>
                                    <p v-if="driver.hauler.length != 0" class="p-0 m-0">
                                        {{ driver.hauler[0].name }}
                                    </p>
                                    <p class="text-danger p-0 m-0" v-else>
                                        NO HAULER
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"  v-if="totalPrint.length > 5">
                             <div class="row text-center">
                            <div class="col">
                                <span class="text-muted font-italic">
                                There are {{ totalPrint.length - 5 }} more to print
                                </span>
                            </div>
                        </div>
                        </li>
                  </ul>
                </div>
                </div>
            </div>
        </div>

        <!-- Total Assigned Modal -->
        <div class="modal fade" id="totalAssigned" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Total Assigned Shipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <ul class="list-group list-group-flush">
                    <li v-for="(n, index) in 3" :key="index"  class="list-group-item">
                        <div class="row" v-for="(shipment, key) in assignedShipment.slice(0)[n]" :key="key">
                            <div class="col">
                                <span class="display-4 font-weight-light">
                                    {{ shipment }}
                                </span>
                                <p class="mt-0 pt-0 text-muted" style="font-size: 25px">
                                    {{ key }}
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                             <div class="row text-center">
                            <div class="col">
                                <span class="text-muted font-italic">
                                    As of today: {{ today() }}
                                </span>
                            </div>
                        </div>
                        </li>
                  </ul>
                </div>
                </div>
            </div>
        </div>

        <!-- Total Truck in plant Modal -->
        <div class="modal fade" id="totalTrucks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Total Trucks In Plant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <ul class="list-group list-group-flush">
                    <li v-for="(n, index) in 3" :key="index"  class="list-group-item">
                        <div class="row" v-for="(plant, key) in trucksInPlant.slice(0)[n]" :key="key">
                            <div class="col">
                                <span class="display-4 font-weight-light">
                                    {{ plant }}
                                </span>
                                <p class="mt-0 pt-0 text-muted" style="font-size: 25px">
                                    {{ key }}
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                             <div class="row text-center">
                            <div class="col">
                                <span class="text-muted font-italic">
                                    As of today: {{ today() }}
                                </span>
                            </div>
                        </div>
                        </li>
                  </ul>
                </div>
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
            trucksInPlant: [],
            assignedShipment: [],
            totalPickup: [],
            totalPrint: [],
            loading1: false,
            loading2: false,
            loading3: false,
            loading4: false,
        }
    },

    created() {

        this.getTrucksInPlant()
        this.getAssignedShipment()
        this.getTotalPrint()
        this.getTotalPickup()

    },

    methods: {

        getTrucksInPlant() {
            this.loading1 = true
            axios.get('/totalTrucksInPlant')
                .then( response => {
                    this.trucksInPlant = response.data
                    this.loading1 = false
                });
        },

        getAssignedShipment() {
            this.loading2 = true
            axios.get('/totalAssignedShipment')
                .then( response => {
                    this.assignedShipment = response.data
                    this.loading2 = false
                });
        },

        getTotalPickup() {
            this.loading3 = true
            axios.get('/totalPickup')
                .then( response => {
                    this.totalPickup = response.data
                    this.loading3 = false
                });
        },

        getTotalPrint() {
            this.loading4 = true
            axios.get('/totalForPrint')
                .then( response => {
                    this.totalPrint = response.data
                    this.loading4 = false
                });
        },

        today() {
            return moment().format('MMMM DD, YYYY');
        }

     

    }

}
</script>
<style scoped>
    .disabled {
        cursor: not-allowed
    }
</style>

