<template>


  <div class="col s12">
        <div class="card grey lighten-4"  v-for="setting in settings">
            <div class="card-content gray-text">
              <span class="card-title">Emailing User</span>
              <p>
              <input  id="first_name" type="text" :value="setting.user.name" class="validate" disabled>
              </p>
            </div>
            <div class="card-action">
              <a :href="setting_link + setting.id + '/edit'">Edit Setting</a>
            </div>
          </div>
</div>


</template>

<script>
export default {
    data() {
        return {
            setting_link: '/settings/',
            settings: []
        }
    },
    created() {
        axios.get('/settingsJson')
        .then(response => this.settings = response.data);
    }

}
</script>