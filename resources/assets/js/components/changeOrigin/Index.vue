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
                        <a class="nav-link active" data-toggle="tab" href="#expected-arrivals" role="tab"> Change Origin Requests </a>
                    </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="expected-arrivals" role="tabpanel">

                    <change-origin-data url="/driver_rfid/public/api/change-origins"></change-origin-data>

                </div><!-- end tab 2 -->


            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->



</template>

<script>

import ChangeOriginData from './ChangeOriginData';

export default {

    components: {
        ChangeOriginData
    },

    data() {
        return {
            loading: false,
            changeOrigins: []
        }
    },

    // mounted() {
    //     this.getChangeOrigin()
    // },

    // methods: {
    //     getChangeOrigin() {
    //         this.loading = true
    //         axios.get('/api/change-origins')
    //         .then(response => {
    //             this.changeOrigins = response.data
    //             this.loading = false;
    //         })
    //     }
    // }
}
</script>
