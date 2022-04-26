<template>
    <div>
        <div class="container">
            <h1>Login</h1>
            <div v-if="errors.length">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" v-model="user.email">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password"  id="password" v-model="user.password">
            </div>

        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button @click="login">Login</button>
        </div>
    </div>
</template>

<script>
import Auth from '../Auth.js';

export default {
    data() {
        return {
            errors: [],
            user: {
                email: '',
                password: '',
            }
        };
    },

    methods: {
        login() {
            this.axios.post('http://127.0.0.1:8000/api/login', this.user)
                .then(({data}) => {
                    Auth.login(data.access_token, data.user); //set local storage
                    this.$router.push('/dashboard');
                })
                .catch((error) => {
                    // console.log(error.response);
                    if (error.response.status == 401) {
                        this.errors.push("We couldn't verify your account details.");
                    } else {
                        this.errors.push("Something went wrong, please refresh and try again.");
                    }
                });
        }
    }
}
</script>
