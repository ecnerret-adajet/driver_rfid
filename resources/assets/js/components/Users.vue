<template>
    <div>
         <ul class="collapsible popout" data-collapsible="accordion">
            <li>
                <div class="collapsible-header active">Users</div>
                <div class="collapsible-body grey lighten-5">

                     <div class="row">
                        <div class="input-group search pull-right">
                            <span class="input-group-addon opener">
                            <i class="material-icons">search</i>
                            </span>
                            <input type="text" v-model="searchUser"  class="form-control" placeholder="Search">
                            <span class="input-group-addon">
                            <i class="material-icons">more_vert</i>
                            </span>
                            <span class="input-group-addon opener">
                            <i class="material-icons">clear</i>
                            </span>
                        </div>
                    </div>

                    <div v-if="!loading">
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                     
                        <tr v-for="user in filteredUser">
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td v-for="role in user.roles">
                                <div class="chip blue lighten-3 white-text">
                                    {{role.name}}
                                </div>
                            </td>
                            <td>{{ moment(user.last_login_at) }}</td>
                            <td>
                                <i class="material-icons">delete</i>
                            </td>
                        </tr>
                       
                        </tbody>
                    </table>
                      </div>

                        <div class="center-align" style="padding-top: 50px" v-if="loading">
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
            </li>
            <li>
                <div class="collapsible-header">Roles</div>
                <div class="collapsible-body"></div>
            </li>
        </ul>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data() {
        return {
            searchUser: '',
            loading: false,
            users: [],
        }
    },

    created() {
        this.getUsers()
    },

    methods: {
        getUsers() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/usersJson')
            .then(response => {
                this.users = response.data
                this.loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM  d, Y h:m:s A');
        }
    },

    computed: {
        filteredUser() {
            var user_array = this.users;
            var searchUser = this.searchUser;

            if(!searchUser) {
                return user_array;
            }

            searchUser = searchUser.trim().toLowerCase();

            user_array = user_array.filter(function(item) {
                if(item.name.toLowerCase().indexOf(searchUser) !== -1) {
                    return item;
                }
            })

            return user_array;

        }
    }


}
</script>