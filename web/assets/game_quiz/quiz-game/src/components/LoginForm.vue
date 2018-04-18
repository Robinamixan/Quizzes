<template>
    <div class="login_panel">
        <div id="error_message">{{error_message}}</div>
        <form id="login_form">
            <input
                    type="text"
                    id="username"
                    name="_username"
                    class="form-control input-lg"
                    placeholder="Login"
                    required
                    v-model="request_login_data.username"/>

            <input
                    type="password"
                    id="password"
                    name="_password"
                    class="form-control input-lg"
                    placeholder="Password"
                    required
                    v-model="request_login_data.password"/>
            <div class="pwstrength_viewport_progress"></div>
            <br>
            <div>
                <router-link class="link" :to="{ name: 'RegistrationForm' }">Create account</router-link>
                or
                <router-link class="link" :to="{ name: 'LoginForm' }">reset password</router-link>

            </div>
        </form>
        <div id="login_buttons">
            <button class="btn btn-lg btn-primary btn-block" @click="requestLogin">Sign in</button>
            <button class="btn btn-default" @click="testConnection">Test Connection</button>
        </div>
        <!--/api/users/registration-->
    </div>
</template>

<script>
    export default {
        name: 'LoginForm',
        data()
        {
            return {
                msg: 'Welcome to Your Vue App',

                request_login_data: {
                    grant_type: 'password',
                    client_id: '1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4',
                    client_secret: '4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k',
                    username: 'admin',
                    password: 'admin',
                },
                error_message: ''

            }
        },
        methods: {
            requestLogin()
            {
                this.$http.post(
                  'http://quizzes.loc/oauth/v2/token',
                  this.request_login_data,
                  {
                      headers: {
                          'Content-Type': 'application/json',
                      },
                      emulateJSON: true
                  }
                )
                  .then(
                    response => {
                        this.error_message = '';
                        this.$globalVariables.error_access = false;
                        this.$globalVariables.access_data = response.body;
                        this.requestUserProfile(this.request_login_data.username);
                    },
                    error => {
                        this.$globalVariables.error_access = true;
                        this.error_message = error.body.error_description;
                        console.error(error);
                    }
                  );
            },
            requestUserProfile(username)
            {
                this.$http.get(
                  'http://quizzes.loc/api/users/' + username + '/profile',
                  {
                      params: {},
                      headers: {
                          'Content-Type': 'application/json',
                          'Authorization': 'Bearer ' + this.$globalVariables.access_data.access_token,
                      },
                      emulateJSON: true
                  }
                )
                  .then(
                    response => {
                        console.log(response.body);
                        this.$globalVariables.user_data.username = response.body.username;
                        this.$globalVariables.user_data.full_name = response.body.full_name;
                        this.$globalVariables.user_data.email = response.body.email;
                    },
                    error => {
                        console.error(error);
                    }
                  );
            },
            testConnection()
            {
                if (!this.$globalVariables.error_access) {
                    this.$http.post('http://quizzes.loc/api/hello',
                      this.request_login_data,
                      {
                          headers: {
                              'Content-Type': 'application/json',
                              'Authorization': 'Bearer ' + this.$globalVariables.access_data.access_token,
                          },
                          emulateJSON: true
                      }
                    )
                      .then(
                        response => {
                            console.log(response.body);
                        },
                        error => {
                            console.error(error);
                        }
                      );
                }
            },
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .login_panel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #login_form, #login_buttons {
        width: 50%;
    }
</style>
