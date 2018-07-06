<template>
    <div class="list-group">
        <div class="list-group-item" v-for="track in tracks" :key="track.id">
            <div class="d-flex flex-column align-items-stretch w-100 flex-nowrap">
                <div class="d-flex flex-row justify-content-between w-100">
                    <div class="d-flex one-line mr-3 track-line">
                        <a href="/en-us/search?SongSearch" :title="'Search for tracks with the name ' + track.title">
                            {{ track.title }}
                        </a>&nbsp;
                        -&nbsp;
                        <a href="/en-us/search?SongSearch" :title="'Search  ' + track.title + ' artist tracks'">
                            {{ track.artist }}
                        </a>
                    </div>
                    <div class="d-flex">
                        <b-button-group size="sm" aria-label="Действия с треком">
                            <b-button :data-id="track.id" variant="secondary" size="xs" class="listenTrack">
                                <font-awesome-icon icon="play" />
                            </b-button>
                            <b-button variant="secondary" size="xs"
                                      :href="'/en-us/get-track?id=' + track.id"
                                      :title="'Download track &quot;' + track.title + ' - ' + track.artist +'&quot;'">
                                <font-awesome-icon icon="download" />
                            </b-button>
                        </b-button-group>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="waveform w-100 d-block"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import gql from 'graphql-tag'

  const request = gql`query getTracks($limit: Int, $order: String, $query: String){
    tracks(limit: $limit, order: $order, query: $query) {
        id
        title
        artist
    }
  }`

  export default {
    name: 'tracksList',
    data: () => ({
      tracks: [],
    }),
    props: {
        searchQuery: String
    },
    apollo: {
      tracks: {
        query: request,
        variables() {
            return {
                query: this.searchQuery,
                limit: 10,
                order: '`added` DESC'
            }
        },
        loadingKey: 'loading',
        error (error) {
          console.error('We\'ve got an error!', error)
        }
      }
    },
  }
</script>