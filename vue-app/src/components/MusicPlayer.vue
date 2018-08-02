<template>
    <div class="card w-100 h-100">
        <div class="row">
            <div class="col">
                <img src="http://via.placeholder.com/800x800" class="img-fluid w-100">
                <h4 class="text-center mt-2 px-1">{{ track.title }}<small class="w-100 d-block">{{ track.artist }}</small></h4>
            </div>
            <small>{{ track.url }}</small>
        </div>
        <div class="row">

        </div>
    </div>
</template>

<script>
    import gql from 'graphql-tag'

    const getTrackRequest = gql`query getTrack($id: Int!){
    track(id: $id) {
        id
        title
        artist
        url
    }
  }`

    export default {
        name: "MusicPlayer",
        data () {
            return {
                track: {
                    id: null,
                    title: '',
                    artist: '',
                    url: ''
                }
            };
        },
        props: {
            id: String
        },
        apollo: {
            track: {
                query: getTrackRequest,
                variables() {
                    return {
                        id: parseInt(this.id)
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

<style scoped>

</style>