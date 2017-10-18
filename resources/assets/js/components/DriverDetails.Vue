<template>


</template>

<script>
import moment from 'moment';
export default {
    data() {
        return {
            loading: fales,
            data: [],
        }
    },

    created() {
        this.getDriver()
    },

    methods: {
        getDriver() {
            this.loading = true
            axios.get('/driver_rfid/public/drivers/{driver}')
            .then(response => {
                this.data = response.data
                this.loading = false
            });
        }
    }

}

</script>