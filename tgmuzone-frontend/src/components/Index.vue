<template>
    <div class="container pt-3">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-3 pb-2">Track search</h1>
                <div class="lead">
                    <form id="w0" action="/site/search" method="get">
                        <div class="form-group field-songsearch-query">
                            <input type="text" id="songsearch-query"
                                   class="form-control form-control-lg text-center" name="SongSearch[query]"
                                   placeholder="Track title, album, or artist">
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
  import TracksList from './TracksList.vue'

  export default {
    components: {TracksList},
    name: 'index',
    data () {
      return {
        msg: 'Welcome to Vue.js App'
      }
    }
  }
</script>