<template>
    <div>

        <div class="row pb-5 pt-3">
                <div class="col-sm-12">
                    <ul class="list-group">

            <li v-for="barrier in entries" class="list-group-item pb-0 pt-0" :style="'border-color: ' + { '#28a745':  barrier.availability == 1, '#d58393' : barrier.availability != 1  }">
                <div class="row">
                    <div class="col-sm-6 p-2" :style="'border-right: 1px solid ' + {  '#28a745' : barrier.availability == 1, '#d58393' : barrier.availability != 1 }">

                         <span v-if="barrier.availability">
                            <a class="btn btn-sm btn-success pull-right btn-outline disabled" href="#">
                                ACTIVE
                            </a>
                         </span>
                         <span v-else>
                             <a class="btn btn-sm btn-danger pull-right btn-outline disabled" href="#">
                                INACTIVE
                            </a>
                         </span>

                        <img class="img-responsive rounded" style="height: 500px; width: auto; margin-left: 50px;" :src="'/driver_rfid/public/storage/' + barrier.avatar" align="middle">
                    
                    </div>
                    <div class="col-sm-6">
           
                <div class="row"> 
                <table class="table table-bordered mb-0">
                    <tr>
                        <td colspan="2">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            <span style="font-size: 35px;">
                            {{barrier.driver}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small class="text-muted">PLATE NUMBER:</small><br/>
                            <span style="font-size: 35px;">
                                {{barrier.plate_number}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            <span style="font-size: 35px;">
                                {{barrier.hauler_name}}
                             </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <small class="text-muted">PLANT IN:</small><br/>

                         <span style="font-size: 35px;" v-if="barrier.inLocalTime">
                            {{ moment(barrier.inLocalTime.date)}} 
                        </span>

                          <span style="font-size: 35px;" v-else>
                            NO IN  
                        </span>

                        </td>
                    </tr>
                    <tr>
                     <td>
                      <small class="text-muted">PLANT OUT:</small><br/>

                         <span style="font-size: 35px;" v-if="barrier.outLocalTime">
                         {{ moment(barrier.outLocalTime.date)}} 
                        </span>

                         <span style="font-size: 35px;" v-else>
                            NO OUT
                        </span>

                        </td>
                    </tr>
                </table>
                </div>
           
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
            }
        },

        created() {
            this.getEntries()
        },

        methods: {
            getEntries() {
                axios.get('/driver_rfid/public/barrierApi')
                .then(response => this.entries = response.data);

                setTimeout(this.getEntries, 1000);
            },

            moment(date) {
                return moment(date).format('MMMM D, Y h:m:s A');
            },
        }
    }
</script>