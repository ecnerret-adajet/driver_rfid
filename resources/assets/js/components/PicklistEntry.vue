<template>
    <div>
       
      

       <table class="table table-bordered">
           <tr>
               <td width="10%">
                <img v-if="entry.image" :src="avatar_link + entry.image.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="middle">
                <img v-else :src="avatar_link + entry.avatar" class="rounded-circle" style="height: 100px; width: auto;"  align="middle">
               </td>
               <td class="pt-3">
                   <button class="float-right btn btn-secondary btn-sm">CANCEL</button>
                   <span class="d-block">{{ entry.name }}</span>
                   <span v-if="entry.truck" class="d-block">{{ entry.truck[0].plate_number }}</span>
                   <span v-if="entry.hauler" class="d-block">{{ entry.hauler[0].name }}</span>
                   
               </td>
           </tr>
       </table>

       <table class="table table-bordered">
           <tr>
               <td colspan="3">
                   <small class="text-muted text-uppercase d-block">Shipment No:</small>
                   <span class="font-weight-light h4">00001231231</span>
               </td>
           </tr>
           <tr>
               <td>
                   <small class="text-muted text-uppercase d-block">Plant / store:</small>
                   <span class="font-weight-light h5">LFUG MANILA</span>
               </td>
               <td>
                   <small class="text-muted text-uppercase d-block">Picking Date:</small>
                   <span class="font-weight-light h5">July 17, 2018 2:00 AM</span>
               </td>
               <td>
                   <small class="text-muted text-uppercase d-block">Loading Date:</small>
                   <span class="font-weight-light h5">July 17, 2018 5:00 AM</span>
               </td>
           </tr>
           <tr>
               <td colspan="3">
                   <small class="text-muted text-uppercase d-block">Route:</small>
                   <span class="font-weight-light h5">Mariveles Bataan, ph</span>
               </td>
           </tr>
       </table>

       <table class="table table-bordered">
           <tr>
               <td colspan="4">
                   <small class="text-muted text-uppercase d-block">Ship to party:</small>
                   <span class="font-weight-light h4">124235232</span>
               </td>
           </tr>
           <tr>
               <td>
                   <small class="text-muted text-uppercase d-block">Plant / store:</small>
                   <span class="font-weight-light h5">LFUG MANILA</span>
               </td>
               <td>
                   <small class="text-muted text-uppercase d-block">Truck Tare weight:</small>
                   <span class="font-weight-light h5">100 MT</span>
               </td>
               <td>
                   <small class="text-muted text-uppercase d-block">Product Gross weight</small>
                   <span class="font-weight-light h5">90 MT</span>
               </td>
               <td>
                   <small class="text-muted text-uppercase d-block">Product Net weight</small>
                   <span class="font-weight-light h5">190 MT</span>
               </td>
           </tr>
           <tr>
               <td colspan="2">
                   <small class="text-muted text-uppercase d-block">Check-in Date:</small>
                   <span class="font-weight-light h5">2018-07-17</span>
               </td>
               <td colspan="2">
                   <small class="text-muted text-uppercase d-block">Check-in Time:</small>
                   <span class="font-weight-light h5">1:45 PM</span>
               </td>
           </tr>
       </table>

       <div class="table-responsive">
           <table class="table table-hover ">
  <thead>
    <tr class="table-dark">
      <th scope="col">DO</th>
      <th scope="col">Item</th>
      <th scope="col">Material</th>
      <th scope="col">Description</th>
      <th scope="col">Batch</th>
      <th scope="col">Gross</th>
      <th scope="col">Net</th>
      <th scope="col">Sale</th>
      <th scope="col">Base</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
      <td>@mdo</td>
      <td>@mdo</td>
      <td>@mdo</td>
      <td>@mdo</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
      <td>@fat</td>
      <td>@fat</td>
      <td>@fat</td>
      <td>@fat</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td colspan="2">Larry the Bird</td>
      <td>@twitter</td>
      <td>@twitter</td>
      <td>@twitter</td>
      <td>@twitter</td>
      <td>@twitter</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>
        </div>


        <div class="row mt-4">
            <div class="col">
                <button class="btn rounded-0 btn-outline-primary btn-lg btn-block text-uppercase active">Driver Confirm</button>
            </div>
            <div class="col">
                <button class="btn rounded-0 btn-outline-primary btn-lg btn-block text-uppercase">Checker Confirm</button>
            </div>
        </div>

    </div>
</template>
<script>
export default {

    props: ['driver_id'],

    data() {
        return {
            entry: [],
            avatar_link: '/driver_rfid/public/storage/',
        }
    },

    created() {
        this.getEntry()
    },

    methods: {
        getEntry() {
            axios.get('/driver_rfid/public/picklistEntry/' + this.driver_id)
            .then(response => this.entry = response.data);
        }
    }

    
}
</script>
