import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import LoginForm from '@/components/LoginForm'
import RegistrationForm from '@/components/RegistrationForm'
import QuizzesList from '@/components/QuizzesList'

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
        },
        {
            path: '/quizzes/list',
            name: 'QuizzesList',
            component: QuizzesList
        }
    ]
})
