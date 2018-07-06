import Vue from 'vue'
import App from './App.vue'
import router from './router'
import BootstrapVue from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faDownload, faPlay } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { ApolloClient } from 'apollo-client'
import { HttpLink } from 'apollo-link-http'
import { InMemoryCache } from 'apollo-cache-inmemory'
import VueApollo from 'vue-apollo'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '@/assets/css/custom.css'

const httpLink = new HttpLink({
    uri: 'https://api.tgmuzone.com.ua/graphQL',
    credentials: 'include'
})

const apolloClient = new ApolloClient({
    link: httpLink,
    cache: new InMemoryCache(),
    connectToDevTools: true,
})

Vue.use(VueApollo)

library.add(faDownload, faPlay)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

Vue.use(BootstrapVue);

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})

new Vue({
  router,
  provide: apolloProvider.provide(),
  render: h => h(App)
}).$mount('#app')
