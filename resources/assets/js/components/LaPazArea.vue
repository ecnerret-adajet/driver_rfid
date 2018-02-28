<template>
    <div>

        <div class="row pb-5 pt-3">
                <div class="col-sm-12">
                    <ul class="list-group">

            <li v-for="(barrier, i) in entries" :key="i" class="rounded-0 list-group-item pb-0 pt-0 rounded-0"  :class="{ 'list-group-item-danger' : barrier.availability != 1 }">

                <div class="row">
                    <div class="col-sm-6 p-3 text-center">
                        
                        <div v-if="barrier.availability == 0" class="border border-danger p-3" style="position: absolute; top: 45%; left: 30%; height: 150xp; width: 400px;">
                            <span class="display-4 text-danger text-uppercase" style="font-weight: bold">
                                DEACTIVATED
                            </span>
                        </div>

                        <img class="img-responsive rounded-circle mx-auto" :class="{ 'deactived-img' : barrier.availability == 0 }" style="height: 450px; width: auto;" :src="'/driver_rfid/public/storage/' + barrier.avatar" align="middle">
                    
                    </div>
                    <div class="col-sm-6 p-0 border border-top-0 border-right-0 border-bottom-0">


                        <ul class="list-group list-group-flush" :class="{ 'text-muted' : barrier.availability == 0 }">

                            <li class="list-group-item" :class="{ 'list-group-item-danger' : barrier.availability != 1 }">
                                <small class="text-muted">DRIVER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                {{barrier.driver}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : barrier.availability != 1 }">
                                <small class="text-muted">PLATE NUMBER:</small><br/>
                                <span style="font-size: 35px;">
                                    {{barrier.plate_number}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : barrier.availability != 1 }">
                                <small class="text-muted">HAULER NAME:</small><br/>
                                <span style="font-size: 35px;">
                                    {{barrier.hauler_name}}
                                </span>
                            </li>
                            <li class="list-group-item" :class="{ 'list-group-item-danger' : barrier.availability != 1 }">
                                <small class="text-muted">PLANT IN:</small><br/>
                                <span style="font-size: 35px;" v-if="barrier.inLocalTime">
                                    {{ moment(barrier.inLocalTime.date)}} 
                                </span>

                                <span style="font-size: 35px;" v-else>
                                    NO IN  
                                </span>
                            </li>
                             <!-- <li class="list-group-item" v-if="i === 0" :class="{ 'list-group-item-danger' : barrier.availability != 1, 'list-group-item-primary' : i===0 }">
                                <span class="text-dark">TRUCKS IN PLANT:</span><br/>
                                 <span style="font-size: 40px;">
                                    {{ currentTrucks }}
                                </span>
                            </li> -->
                        </ul>
           
           
                    </div>
                </div><!--end row -->

                 </li>

                    </ul>
                </div>
        </div>

    </div>
</template>
<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                entries: [],
                // currentTrucks: []
            }
        },

        created() {
            this.getEntries()
            // this.getTruckInPlant()
        },

        methods: {
            getEntries () {
                axios.get('/driver_rfid/public/laPazAPI')
                .then(response => this.entries = response.data);

                setTimeout(this.getEntries, 2000);
            },

            // getTruckInPlant () {
            //     axios.get('/driver_rfid/public/getTotalTrucksInPlant')
            //     .then(response => this.currentTrucks = response.data);
            //     setTimeout(this.getTruckInPlant, 3000);
            // },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        }
    }
</script>

<style scoped>
    .deactived-img {
        opacity: 0.5;
        filter: alpha(opacity=50);
    }
</style>