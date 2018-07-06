import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import About from './views/About.vue'
import Feedback from './views/Feedback.vue'
import Statistics from './views/Statistics.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/home',
      name: 'home',
      component: Home
    },
    {
      path: '/statistics',
      name: 'statistics',
      component: Statistics
    },
    {
      path: '/about',
      name: 'about',
      component: About
    },
    {
      path: '/feedback',
      name: 'feedback',
      component: Feedback
    },
    { path: '*', redirect: '/home' },
  ]
})
