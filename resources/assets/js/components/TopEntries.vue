<template>


<div>


<h5 style="font-weight: 100 ">
    Trucks Less Than 3 Hours
</h5>

<table class="table table-striped">
  <thead>
    <tr class="text-muted" style="text-transform:uppercase; font-size: 12px;">
      <th scope="col">Driver Name</th>
      <th scope="col">Plate Number</th>
      <th scope="col">Plant IN</th>
      <th scope="col">Plant Out</th>
      <th scope="col">Between</th>
    </tr>
  </thead>
  <tbody v-if="!loading">
    <tr v-for="(entries, index) in idleMoreThan3Houres" v-if="dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) < 3">

      <td>{{ entries.driver }}</td>
      <td>{{ entries.plate_number }} </td>
      <td>
        
        <span v-if="entries.inLocalTime == null">
              <span class="text-muted">
                NO TIME IN
            </span>
        </span>
        <span v-else>
            {{ moment(entries.inLocalTime.date) }}
        </span>
     </td>
      <td>
        <span v-if="entries.outLocalTime == null">
            <span class="text-muted">
                NO TIME OUT
            </span>
        </span>
        <span v-else>
            {{ moment(entries.outLocalTime.date) }}
        </span>
      </td>
      <td>
          <span v-if="entries.inLocalTime == null || entries.outLocalTime == null">
            <span class="text-muted">
                NO PAIRED TIME
            </span>
        </span>
        <span v-else>
            {{ dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) }} hour(s)
        </span>
      </td>

    </tr>
  </tbody>

  <tbody v-if="loading">
        <tr>
            <td colspan="5">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                </svg>	
            </div>

            </td>
         </tr>
  </tbody>


</table>



<h5 style="font-weight: 100 ">
    Trucks 3 to 6 hours idle time
</h5>

<table class="table table-striped">
  <thead>
    <tr class="text-muted" style="text-transform:uppercase; font-size: 12px;">
      <th scope="col">Driver Name</th>
      <th scope="col">Plate Number</th>
      <th scope="col">Plant IN</th>
      <th scope="col">Plant Out</th>
      <th scope="col">Between</th>
    </tr>
  </thead>
  <tbody v-if="!loading">
    <tr v-for="(entries, index) in idleMoreThan3Houres" v-if="dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) > 3 && dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) < 6">

      <td>{{ entries.driver }}</td>
      <td>{{ entries.plate_number }} </td>
      <td>
        
        <span v-if="entries.inLocalTime == null">
              <span class="text-muted">
                NO TIME IN
            </span>
        </span>
        <span v-else>
            {{ moment(entries.inLocalTime.date) }}
        </span>
     </td>
      <td>
        <span v-if="entries.outLocalTime == null">
            <span class="text-muted">
                NO TIME OUT
            </span>
        </span>
        <span v-else>
            {{ moment(entries.outLocalTime.date) }}
        </span>
      </td>
      <td>
          <span v-if="entries.inLocalTime == null || entries.outLocalTime == null">
            <span class="text-muted">
                NO PAIRED TIME
            </span>
        </span>
        <span v-else>
            {{ dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) }} hour(s)
        </span>
      </td>

    </tr>
  </tbody>

  <tbody v-if="loading">
        <tr>
            <td colspan="5">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                </svg>	
            </div>

            </td>
         </tr>
  </tbody>


</table>



<h5 style="font-weight: 100 ">
    Trucks More Than 7 Hours
</h5>

<table class="table table-striped">
  <thead>
    <tr class="text-muted" style="text-transform:uppercase; font-size: 12px;">
      <th scope="col">Driver Name</th>
      <th scope="col">Plate Number</th>
      <th scope="col">Plant IN</th>
      <th scope="col">Plant Out</th>
      <th scope="col">Between</th>
    </tr>
  </thead>
  <tbody v-if="!loading">
    <tr v-for="(entries, index) in idleMoreThan3Houres" v-if="dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) > 7">

      <td>{{ entries.driver }}</td>
      <td>{{ entries.plate_number }} </td>
      <td>
        
        <span v-if="entries.inLocalTime == null">
              <span class="text-muted">
                NO TIME IN
            </span>
        </span>
        <span v-else>
            {{ moment(entries.inLocalTime.date) }}
        </span>
     </td>
      <td>
        <span v-if="entries.outLocalTime == null">
            <span class="text-muted">
                NO TIME OUT
            </span>
        </span>
        <span v-else>
            {{ moment(entries.outLocalTime.date) }}
        </span>
      </td>
      <td>
          <span v-if="entries.inLocalTime == null || entries.outLocalTime == null">
            <span class="text-muted">
                NO PAIRED TIME
            </span>
        </span>
        <span v-else>
            {{ dateDiff(entries.inLocalTime.date, entries.outLocalTime.date) }} hour(s)
        </span>
      </td>

    </tr>
  </tbody>

  <tbody v-if="loading">
        <tr>
            <td colspan="5">
                  <div class="center-align" style="display: flex; align-items: center; justify-content: center;">
                <svg class="spinner" width="80px" height="80px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                </svg>	
            </div>

            </td>
         </tr>
  </tbody>


</table>


</div>



</template>
<script>
import moment from 'moment';
export default {
    data() {
        return {
            loading: false,
            topEntries: []
        }
    },

    created() {
        this.getTopEntries()
    },

    methods: {
        getTopEntries() {
            this.loading = true
            axios.get('/driver_rfid/public/detailEntries')
            .then(response => {
                this.topEntries = response.data
                this.loading = false
            })
        },

        moment(date) {
            return moment(date).format('MMM Do YY h:m:s A');
        },

        dateDiff(startTime, endTime) {
            //    var ms = moment(moment(endTime, "DD/MM/YYYY HH:mm:ss").diff(moment(startTime, "DD/MM/YYYY HH:mm:ss"))).format("HH:mm:ss");
            //    var duration = moment.duration(endTime.diff(startTime));
            //    return hours = duration.asHours();
            var a = moment(startTime);   
            var b = moment(endTime);   
            return b.diff(a, 'hours');
       },
    },

    computed: {
        idleMoreThan3Houres() {
            var entries_array = this.topEntries;

              entries_array = entries_array.filter(function(item){
                if(item.inLocalTime != null && item.outLocalTime != null){
                    return item;
                }
            })

             return entries_array

        } 
    }

}
</script>

