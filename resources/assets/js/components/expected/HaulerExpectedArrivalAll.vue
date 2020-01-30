<template>

  <div class="card mx-auto mb-3">
        <div class="card-header">
        Expected Arrivals

        <a class="btn btn-primary btn-sm pull-right" href="#">
        Back
        </a>
        </div>
        <div class="card-body">

             <!-- <pickups></pickups> -->

              <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#expected-arrivals" role="tab"> Expected Arrival </a>
                    </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="expected-arrivals" role="tabpanel">

                    <hauler-expected-data url="/api/haulers-arrivals"></hauler-expected-data>

                </div><!-- end tab 2 -->


            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->



</template>

<script>
import HaulerExpectedData from '../expected/HaulerExpectedData';

export default {
    components: {
        HaulerExpectedData,
    },
    data() {
        return {
            filter: '',
            startDate: '',
            endData: '',
        }
    }
}
</script>
