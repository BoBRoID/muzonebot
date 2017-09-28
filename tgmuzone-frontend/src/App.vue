<template>
  <div id="app">
    <b-navbar toggleable="lg" type="light" variant="light">
      <div class="container">
        <b-nav-toggle target="nav_collapse"></b-nav-toggle>
        <b-navbar-brand href="/">MuzOne</b-navbar-brand>
        <b-collapse is-nav id="nav_collapse" class="justify-content-end">
          <b-nav is-nav-bar class="navbar">
            <b-nav-item-dropdown :text="languages.label">
              <b-dropdown-item v-for="language in languages.items" :href="language.url">{{ language.label }}</b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item v-for="item in menuItems" :href="item.url">{{ item.label }}</b-nav-item>
            <b-nav-item href="#" data-toggle="modal" data-target="#loginModal">Authorization</b-nav-item>
          </b-nav>
        </b-collapse>
      </div>
    </b-navbar>
    <div class="wrap">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  name: 'app',
  data () {
    return {
      menuItems: [
        {
          label: 'Home',
          url: '/'
        },
        {
          label: 'Statistics',
          url: '/stats'
        },
        {
          label: 'About',
          url: '/about'
        },
        {
          label: 'Оставить отзыв',
          url: '/feedback'
        }
      ],
      languages: {
        label: 'English (US) 🇺🇸',
        items: [
          {
            label: 'English (US)',
            url: '/en-us'
          },
          {
            label: 'Português (Brasil)',
            url: '/pt-br'
          },
          {
            label: 'Русский',
            url: '/ru-ru'
          },
          {
            label: 'Українська',
            url: '/uk-ua'
          }
        ]
      }
    }
  }
}
</script>
