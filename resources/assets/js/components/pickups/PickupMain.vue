<template>

  <div class="card mx-auto mb-3">
        <div class="card-header">
        All Pickups

        <a class="btn btn-primary btn-sm pull-right" href="#">
        Back
        </a>
        <a class="btn btn-secondary btn-sm pull-right mr-2" href="/pickups">
        Visit Old Version
        </a>
        </div>
        <div class="card-body">

             <pickups></pickups>

              <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#no-yet-serve" role="tab"> Not Yet Served </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#assigned" role="tab">Current In Plant</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#served" role="tab"> Served </a>
                    </li>


            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="no-yet-serve" role="tabpanel">

                    <pickups-data url="/api/pickups-unserved/"></pickups-data>

                </div><!-- end tab 2 -->

                <div class="tab-pane  pt-3" id="assigned" role="tabpanel">

                    <pickups-data url="/api/pickups-assigned/"></pickups-data>

                </div><!-- end tab1 -->

                <div class="tab-pane pt-3" id="served" role="tabpanel">

                     <pickups-data url="/api/pickups-served/"></pickups-data>

                </div><!-- end tab1 -->



            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->



</template>

<script>
import PickupsData from '../pickups/PickupsData.vue';

export default {
    components: {
        PickupsData,
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
