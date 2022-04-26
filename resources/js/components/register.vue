<template>
    <div>
        <div class="container">
            <h1>Register</h1>
            <div v-if="errors.length">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" v-model="user.name">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="text" id="email" v-model="user.email">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password"  id="password"  v-model="user.password">
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password"  id="password_confirmation"  v-model="user.password_confirmation">
            </div>
            <button @click="register()">Register</button>
        </div>
    </div>

</template>

<script>


export default {
    data() {
        return {
            errors: [],
            user: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        };
    },

    methods: {
        checkForm() {
            if (!this.user.name) {
                this.errors.push("Name required.");
            }
            if (!this.user.email) {
                this.errors.push('Email required.');
            } else if (!this.validEmail(this.user.email)) {
                this.errors.push('Valid email required.');
            }
            if(!this.user.password){
                this.errors.push('Password is required.');
            }
            if(this.user.password != this.user.password_confirmation){
                this.errors.push('Password must be the same.');
            }

            if (!this.errors.length) {
                return true;
            }

            e.preventDefault();
        },
        validEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        register() {
            this.checkForm(),
            this.axios.post('http://127.0.0.1:8000/api/register', this.user)
                .then(({data}) => {
                    this.$router.push('/login');
                })
                .catch((error) => {
                    // console.log(error.response);
                    if (error.response.status == 422) {
                        this.errors.push(error.response.data.errors);
                    } else {
                        this.errors.push("Something went wrong, please refresh and try again.");
                    }
                });
        }
    }
}
</script>
