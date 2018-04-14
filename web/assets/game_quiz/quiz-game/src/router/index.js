import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import LoginForm from '@/components/LoginForm'
import RegistrationForm from '@/components/RegistrationForm'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'HelloWorld',
            component: HelloWorld
        },
        {
            path: '/login',
            name: 'LoginForm',
            component: LoginForm
        },
        {
            path: '/registration',
            name: 'RegistrationForm',
            component: RegistrationForm
        }
    ]
})
