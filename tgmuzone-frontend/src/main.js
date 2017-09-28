// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import { ApolloClient, createNetworkInterface } from 'apollo-client'
import Vue from 'vue'

import VueApollo from 'vue-apollo'
import BootstrapVue from 'bootstrap-vue'

import App from './App'
import router from './router'

Vue.config.productionTip = false

const networkInterface = createNetworkInterface({
  uri: 'http://api.tgmuzone.dev/graphQL',
  opts: {
    credentials: 'same-origin',
    mode: 'no-cors'
  }
})

const apolloClient = new ApolloClient({
  networkInterface,
  connectToDevTools: true
})

Vue.use(VueApollo)

Vue.use(BootstrapVue)

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
  defaultOptions: {
    $loadingKey: 'loading'
  }
})
/* eslint-disable no-new */
new Vue({
  el: '#app',
  apolloProvider,
  router,
  template: '<App/>',
  components: { App }
})
