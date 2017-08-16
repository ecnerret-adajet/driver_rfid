<template>
  <div>
    <ul class="collapsible popout" data-collapsible="accordion">
        <li>
            <div class="collapsible-header active">Overview</div>
            <div class="collapsible-body grey lighten-3" style="padding: 0;">

            <div class="row dash-status">

                <div class="col s3 dash-child">
                    <p>All Trucks</p>
                        <div  v-if="!is_loading">
                            <p class="app-count">
                             {{ home.trucks }}
                            </p>
                        </div>
                        <div v-if="is_loading">
                            <div class="app-loading preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="col s3 dash-child">
                    <p>All Drivers</p>
                        <div  v-if="!is_loading">
                            <p class="app-count">
                             {{ home.drivers }}
                            </p>
                        </div>
                        <div v-if="is_loading">
                            <div class="preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col s3 dash-child">
                    <p>All Haulers</p>
                    <div  v-if="!is_loading">
                            <p class="app-count">
                            {{ home.haulers }}
                            </p>
                        </div>
                        <div v-if="is_loading">
                            <div class="preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col s3  last-dash-child ">
                    <p>All Cards</p>
                        <div  v-if="!is_loading">
                            <p class="app-count">
                            {{ home.cards }}
                            </p>
                        </div>
                        <div v-if="is_loading">
                            <div class="preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row dash-status">
                <div class="col s3 last-dash-status">
                    <p>For Print</p>
                        <div  v-if="!is_loading">
                            <p class="app-count">
                            {{ home.prints }}
                            </p>
                        </div>
                        <div v-if="is_loading">
                            <div class="preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col s3 last-dash-status">
                    <p>Email Confirmation</p>
                    <p class="app-count">
                    0
                    </p>
                </div>
                <div class="col s3 last-dash-status">
                    <p>Total Users</p>
                        <div  v-if="!is_loading">
                            <p class="app-count">
                            {{ home.users }}
                            </p>
                        </div>
                        <div v-if="is_loading">
                            <div class="preloader-wrapper small active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col s3 last-dash-status">
                 
                </div>
            </div>


            </div>
        </li>
        <li>
            <div class="collapsible-header">Second</div>
            <div class="collapsible-body"></div>
        </li>
        <li>
            <div class="collapsible-header">Third</div>
            <div class="collapsible-body"></div>
        </li>
    </ul>
  </div>
</template>

<script>
export default {
    data() {
        return {
            print_link: '/driver_rfid/public/prints',
            home: [],
            is_loading: false
        }
    },

    created() {
      this.getHome()
    },

    methods: {
        getHome() {
            this.is_loading = true
            axios.get('http://localhost/driver_rfid/public/homeJson')
            .then(response => {
                this.home = response.data
                this.is_loading = false
            });
        }
    }
}
</script>