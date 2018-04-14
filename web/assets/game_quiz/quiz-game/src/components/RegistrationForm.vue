<template>
    <div class="registration_panel">
        <div id="registration_form">
            <form class="form-signin" name="register_form" id="register_form">
                <input
                        type="text"
                        id="registration_form_username"
                        name="registration_form[username]"
                        required="required"
                        title="Enter your username"
                        class="form-control"
                        placeholder="User Name"
                        pattern="\w+"
                        v-model="request_registration_data.username">
                <br>
                <input
                        type="password"
                        id="registration_form_password_first"
                        name="registration_form[password][first]"
                        required="required"
                        class="form-control"
                        placeholder="Password"
                        title="Password must contain at least 6 characters"
                        pattern=".{6,}"
                        v-model="form_password_first">
                <input
                        type="password"
                        id="registration_form_password_second"
                        name="registration_form[password][second]"
                        required="required"
                        class="form-control"
                        placeholder="Password"
                        title="Password must contain at least 6 characters"
                        pattern=".{6,}"
                        v-model="form_password_second">
                <br>
                <input
                        type="text"
                        id="registration_form_full_name"
                        name="registration_form[full_name]"
                        class="form-control"
                        placeholder="Name"
                        v-model="request_registration_data.full_name">
                <input
                        type="email"
                        id="registration_form_email"
                        name="registration_form[email]"
                        required="required"
                        class="form-control"
                        placeholder="Email"
                        v-model="request_registration_data.email">
            </form>
            <button id="submitRegistration" class="btn btn-lg btn-primary btn-block" @click="requestRegistration">
                Register
            </button>
        </div>
        <!--/api/users/registration-->


        <form>
            <p class="control">

            </p>

            <p class="control">
                <button class="button is-primary">Register</button>
            </p>
        </form>
    </div>
</template>

<script>
    import VuePassword from 'vue-password'

    export default {
        name: 'RegistrationForm',
        components: {
            VuePassword
        },
        data()
        {
            return {
                msg: 'Welcome to Your Vue App',

                form_password_first: '',
                form_password_second: '',
                request_registration_data: {
                    username: '',
                    password: '',
                    full_name: '',
                    email: '',
                },
            }
        },
        methods: {
            requestRegistration()
            {
                if (this.form_password_first === this.form_password_second) {
                    let sha1 = require('sha1');
                    this.request_registration_data.password = sha1(this.form_password_first)

                    this.$http.post(
                      'http://quizzes.loc/users/registration',
                      this.request_registration_data,
                      {
                          headers: {
                              'Content-Type': 'application/json',
                          },
                          emulateJSON: true
                      }
                    )
                      .then(
                        response => {
                            console.log(response.body);
                        },
                        error => {
                            this.$globalVariables.error_access = true;
                            console.error(error);
                        }
                      );
                } else {
                    alert("passwords not equal!")
                }
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .registration_panel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #registration_form {
        width: 50%;
    }
</style>
