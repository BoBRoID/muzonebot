<template>
  <div class="container pt-3">
    <div class="jumbotron jumbotron-fluid" style="text-align: center; background-color: transparent;">
      <div class="container">
        <h1 class="display-3 pb-2">Track search</h1>
        <div class="lead">
          <form id="w0" action="/site/search" method="get">
            <div class="form-group field-songsearch-query">
              <input id="songsearch-query" class="form-control form-control-lg text-center" v-model="searchQuery" placeholder="Track title, album, or artist" type="text">
              <div class="help-block"></div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Search</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <h3 class="text-center">Last 10 added songs</h3>
    <tracks-list></tracks-list>
    </div>
</template>

<script>
  import TracksList from '../components/TracksList.vue';

  export default {
      name: 'home',
      components: {
          TracksList
      },
      data () {
          return {
              tracks: [],
              searchQuery: null
          }
      },
  }
</script>
