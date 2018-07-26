<template>
    <div class="card w-100 h-100">
        <div class="row">
            <div class="col">
                <img src="http://via.placeholder.com/800x800" class="img-fluid w-100">
                <h4 class="text-center">Track name <small class="w-100 d-block">Track artist</small></h4>
            </div>
        </div>
        <div class="row">

        </div>
    </div>
</template>

<script>
    export default {
        name: "MusicPlayer",
        props: {
            title: String,
            artist: String
        }
    }
</script>

<style scoped>

</style>