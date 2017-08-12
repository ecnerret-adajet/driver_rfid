<template>

    <div class="row">      
    <div class="col l4 m12 s12">
      <div class="card light-blue">
        <div class="card-content">
          <div id="test1" style="display: none;" class="white-text">
              <span class="title">
                TOTAL HAULERS
              </span>
              <h1>
                {{ home.hauler }}
              </h1>
          </div>
          <div id="test2" class="white-text" style="display: none;">
              <span class="title">
                TOTAL TRUCKS
              </span>
              <h1>
                {{ home.truck }}
              </h1>
          </div>

          <div id="test3" style="" class="white-text active">
              <span class="title">
                TOTAL DRIVERS
              </span>
              <h1>
            {{ home.driver }}
              </h1>
          </div>
        </div>
        <div class="card-tabs white">
          <ul class="tabs tabs-fixed-width tabs-transparent">
          <li class="tab">
            <a href="#test1" class="blue-text">
              Total Hauler
            </a>
          </li>
          <li class="tab">
            <a class="light-blue-text" href="#test2">
            Total Trucks
            </a>
          </li>
          <li class="tab">
            <a href="#test3" class="blue-text active">
                 Total Drivers
            </a>
          </li>
          <li class="indicator" style="right: 0px; left: 238px; background-color: #03a9f4;"></li></ul>
          </div>

      </div>
    </div>


    <div class="col l4 m12 s12">
   <div class="card teal darken-2">
            <div class="card-content white-text">
              <span >PENDING TO PRINT</span>
              <h1>
                {{home.print}}
              </h1>
            </div>
            <div class="card-action">
              <a :href="print_link" class="waves-effect waves-light">View details</a>
            </div>
          </div>
  </div>


</div> <!-- end row -->

</template>

<script>
export default {
    data() {
        return {
            print_link: '/driver_rfid/public/prints',
            home: []
        }
    },

    created() {
        axios.get('http://localhost/driver_rfid/public/homeJson')
        .then(response => this.home = response.data);
    }
}
</script>