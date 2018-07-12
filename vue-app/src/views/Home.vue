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
          </form>
        </div>
      </div>
    </div>
    <div v-if="searchQuery">
      <div class="clearfix">
        <div class="float-left"><h5 class="mt-2">Search results for "{{ searchQuery }}":</h5></div>
        <div class="float-right">
          <select v-model="limit">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>
      <tracks-list v-bind:searchQuery="searchQuery" v-bind:searchLimit="limit"></tracks-list>
    </div>
    <div v-else>
      <h3 class="text-center">Last 10 added songs</h3>
      <tracks-list></tracks-list>
    </div>
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
              searchQuery: null,
              limit: 10
          }
      },
  }
</script>
