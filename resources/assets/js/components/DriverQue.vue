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
                                <th>Submission Date</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody v-for="queue in queues">
                                <tr v-for="driver in queue.drivers">
                                        <td>
                                            <img class="rounded-circle" style="height: 60px; width: auto;" :src="avatar_link + driver.id" align="top">
                                        </td>
                                        <td>
                                            {{ driver.name }} 
                                        </td>
                                        <td v-for="truck in driver.trucks">
                                                {{ truck.plate_number }}
                                        </td>
                                        <td v-for="hauler in driver.haulers">
                                                {{ hauler.name }}
                                        </td>
                                        <td>
                                            {{ moment(queue.LocalTime) }}
                                        </td>

                                        <td v-for="truck in driver.trucks">
                                            <span v-if="truck">
                                                {{ getCheckSubmission(truck.plate_number) }}
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
                checkSubmission: []
            }
        },

        created() {
            this.getQueues();
        },

        methods: {
            getQueues() {
                axios.get('/driver_rfid/public/api/queues')
                .then(response => this.queues = response.data);


                // let es = new EventSource('/driver_rfid/public/api/queues');
                // es.addEventListener('message', event => {
                //     let data = JSON.parse(event.data);
                //     this.queues = data.queues;
                // }, false);

                // es.addEventListener('error', event => {
                //     if (event.readyState == EventSource.CLOSED) {
                //         console.log('Event was closed');
                //         console.log(EventSource);
                //     }
                // }, false);
            },

            getCheckSubmission(plate_number) {
                axios.get('/driver_rfid/public/api/checkSubmissionDate' + plate_number)
                .then(response => this.checkSubmission = response.data);

                // let es = new EventSource('/driver_rfid/public/api/checkSubmissionDate' + plate_number);
                // es.addEventListener('message', event => {
                //     let data = JSON.parse(event.data);
                //     this.checkSubmission = data.checkSubmission;
                // }, false);

                // es.addEventListener('error', event => {
                //     if (event.readyState == EventSource.CLOSED) {
                //         console.log('Event was closed');
                //         console.log(EventSource);
                //     }
                // }, false);
            },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        },
       
    }
</script>