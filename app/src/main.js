import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App'
import Home from './components/home'
import CreateTask from './components/task/CreateTask'
import ViewTask from './components/task/ViewTask'
import TaskList from './components/task/TaskList'
import Admin from './components/admin'
import SignIn from './components/user/SignIn'
import api from './api'

Vue.use(api)
Vue.use(VueRouter)

/* eslint-disable no-new */
const routes = [
  { path: '/', component: Home },
  { path: '/dashboard', component: Home },
  { path: '/task/create', component: CreateTask, name: 'create-task' },
  { path: '/task/:id', component: ViewTask },
  { path: '/task/:id/edit', component: CreateTask, name: 'edit-task' },
  { path: '/task', component: TaskList },
  { path: '/admin', component: Admin },
  { path: '/signin', component: SignIn }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  el: '#app',
  template: '<App />',
  components: { App },
  router
})
