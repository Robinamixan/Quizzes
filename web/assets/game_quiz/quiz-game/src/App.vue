<template>
  <div id="app">
    <router-link class="btn btn-dark" :to="{ name: 'LoginForm' }">Login</router-link>
    <router-link class="btn btn-dark" :to="{ name: 'RegistrationForm' }">Registration</router-link>
    <router-link class="btn btn-dark" :to="{ name: 'QuizzesList' }">Quizzes list</router-link>
    <div>{{$globalVariables.user_data.username}}</div>
    <div>{{$globalVariables.user_data.email}}</div>
    <div>{{$globalVariables.user_data.full_name}}</div>
    <img src="./assets/logo.png">
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
  body{
    background-color: #4e555b;
  }

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
