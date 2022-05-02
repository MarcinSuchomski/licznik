<template>
    <div >
        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Licznik</a>
                <i class='far fa-clock' style='font-size:24px'></i>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="/">Home</a>
                        </li>
                        <div class="d-flex"  v-if="!loggedUser">
                        <li class="nav-item">
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/register" class="nav-link">Register</router-link>
                        </li>
                        </div>
                        <li class="nav-item">
                            <router-link to="/dashboard" class="nav-link">Dashboard</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/counter" class="nav-link">Counter</router-link>
                        </li>
                    </ul>
                    <div class="d-flex"  v-if="loggedUser">
                        <a href="javascript:void(0)" @click="logout()" class="nav-item nav-link ">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
            <div class="container" v-if="!loggedUser">
                <h1>Licznik Czasu</h1>
            </div>
            <div class="container" v-else>
                <div>
                    <p> Czesc {{loggedUser.name}}</p>
                    <p> Witam w Szlachetna Paczka licznik App</p>
                </div>
            </div>
        <router-view> </router-view>
    </div>
</template>

<script>
import Auth from './Auth.js';
import dashboard from "./components/dashboard";

export default {
    name: 'dashboard',
    data() {
        return {
            loggedUser: this.auth.user
        };
    },
    mounted() {
        console.log(this.auth.user);
    },
    methods: {
        logout() {
            Auth.logout();
            console.log("called ");
            this.axios.post('logout')
                .then(({data}) => {
                    Auth.logout(); //reset local storage
                    //this.$router.push('/');
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>
