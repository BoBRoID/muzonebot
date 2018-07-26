<template>
  <div id="app">
    <b-navbar toggleable="lg" type="light" variant="light" fixed="top">
      <div class="container">
        <b-nav-toggle target="nav_collapse"></b-nav-toggle>
        <b-navbar-brand href="/">MuzOne</b-navbar-brand>
        <b-collapse is-nav id="nav_collapse" class="justify-content-end">
          <b-navbar-nav class="navbar">
            <b-nav-item-dropdown :text="languages.label">
              <b-dropdown-item v-for="language in languages.items" :href="language.url">{{ language.label }}</b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item v-for="item in menuItems" :to="item.url">{{ item.label }}</b-nav-item>
            <b-nav-item href="#" data-toggle="modal" data-target="#loginModal">Authorization</b-nav-item>
          </b-navbar-nav>
        </b-collapse>
      </div>
    </b-navbar>
    <div class="wrap mt-6">
      <router-view></router-view>
      <slide-panel>
        <MusicPlayer :title="playingSong.title" :artist="playingSong.artist"></MusicPlayer>
      </slide-panel>
    </div>
    <footer class="footer">
      <div class="container">
        <p class="float-left">© Kroshyk and Lazy Penguin 2018</p>
        <p class="float-right">Powered by <a href="https://vuejs.org">Vue</a> and <a href="http://www.yiiframework.com/" rel="external">Yii Framework</a></p>
      </div>
    </footer>
  </div>
</template>

<script>
    import SlidePanel from "./components/SlidePanel";
    import MusicPlayer from "./components/MusicPlayer";
    import gql from 'graphql-tag'


    const request = gql`query lastListenedTrack {
        id
        title
        artist
      }`

    export default {
        name: 'app',
        components: {MusicPlayer, SlidePanel},
        data () {
            return {
                playingSong: {},
                menuItems: [
                    {
                        label: 'Главная',
                        url: {name: 'home'}
                    },
                    {
                        label: 'Статистика',
                        url: {name: 'statistics'}
                    },
                    {
                        label: 'О проекте',
                        url: {name: 'about'}
                    },
                    {
                        label: 'Оставить отзыв',
                        url: {name: 'feedback'}
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
        },
        apollo: {
            tracks: {
                query: request,
                loadingKey: 'loading',
                error (error) {
                    console.error('We\'ve got an error!', error)
                }
            }
        },
    }
</script>