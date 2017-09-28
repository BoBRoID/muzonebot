import Vue from 'vue'
import Router from 'vue-router'
import Index from '@/components/Index'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '',
      name: 'Main',
      component: Index
    },
    {
      path: '/',
      name: 'Main',
      component: Index
    }
  ]
})
