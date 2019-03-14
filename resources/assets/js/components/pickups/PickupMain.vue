<template>

  <div class="card mx-auto mb-3">
        <div class="card-header">
        All Pickups

        <a class="btn btn-primary btn-sm pull-right" href="#">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="#">
        Add New Pickup
        </a>
        </div>
        <div class="card-body">


            <pickup-served></pickup-served>



        </div><!-- end card-body -->
    </div> <!-- end card -->



</template>

<script>
import PickupServed from '../pickups/PickupServed.vue';

export default {
    components: {
        PickupServed,
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
