<template>
    <div class="had-container">
        <div class="row" v-for="i in Math.ceil(drivers.length / 4)">
            <div class="col s3" v-for="driver in drivers.slice((i - 1) * 4, i * 4)">
                <div class="card">
                    <div class="card-content">
                    <span class="card-title">{{ driver.name }}</span>
                    <p>
                        Clasification: {{ driver.clasification.name }} <br/>
                        Edited by: {{ driver.user.name }}
                    </p>
                    </div>
                    <div class="card-action">
                    <form method="POST" v-on:submit.prevent="updateItem(driver.id)">
                        <button  type="submit" class="waves-effect waves-light btn">Mark as printed</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            drivers: [],
        }
    }, 
    created() {
        this.fetchDrivers();
    },
    methods: {
        fetchDrivers() {
            axios.get('/driver_rfid/public/forPrint')
            .then(response => this.drivers = response.data);
        },
        updateItem(id) {
            axios.put('/driver_rfid/public/prints/' + id)
                .then((res) => {
                    this.fetchDrivers()
                })
                .catch((err) => console.error(err));
        }
    }
}
</script>