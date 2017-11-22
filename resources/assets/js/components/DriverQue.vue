<template>
    <div>
        <div class="row">
            <div class="col-sm-12">

                <div class="table-responsive">
                    <table class="table" width="100%" cellspacing="0" style="font-size: 70%">
                        <thead>
                            <tr style="text-transform:uppercase">
                                <th></th>
                                <th>Driver Name</th>
                                <th>Plate Number</th>
                                <th>Hauler</th>
                                <th>Date/Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody v-for="queue in queues">
                                <tr v-for="driver in queue.drivers">
                                        <td>
                                             <img :src="avatar_link + driver.avatar" class="rounded-circle" style="height: 60px; width: auto;"  align="middle">
                                        </td>
                                        <td>
                                            <span v-if="driver.name">
                                                {{ driver.name }} 
                                            </span>
                                            <span v-else>
                                                NO DRIVER
                                            </span>
                                        </td>
                                        <td v-for="truck in driver.trucks">
                                                {{ truck.plate_number }}
                                        </td>
                                         <td v-if="driver.trucks.length == 0">
                                            <span class="text-danger">NO TRUCK</span>
                                        </td>
                                        <td v-for="hauler in driver.haulers">
                                                {{ hauler.name }}
                                        </td>
                                        <td v-if="driver.haulers.length == 0">
                                            <span class="text-danger">NO HAULER</span>
                                        </td>
                                        <td>
                                            <span v-if="moment(queue.LocalTime)">
                                                {{ moment(queue.LocalTime) }}
                                            </span>
                                            <span v-else>
                                                NO TIME
                                            </span>
                                        </td>
                                        <td>
                                             <span v-if="driver.availability == 1">
                                                 <button class="btn btn-success btn-sm disabled">
                                                     ACTIVE
                                                 </button>
                                             </span>
                                             <span v-else>
                                                 <button class="btn btn-danger btn-sm disabled">
                                                     INACTIVE
                                                 </button>
                                             </span>
                                        </td>

                                    </tr>
                                    
                        </tbody>

                    </table>
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
                avatar_link: '/driver_rfid/storage/app/',
                queues: [],
            }
        },

        created() {
            this.getQueues()
        },

        methods: {
            getQueues() {
                axios.get('/driver_rfid/public/api/queues')
                .then(response => this.queues = response.data);

                setTimeout(this.getQueues, 1000);
            },

        
            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        },



    }
</script>