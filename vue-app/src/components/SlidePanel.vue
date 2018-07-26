<template>
    <div>
        <div class="slidepanel">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SlidePanel"
    }
</script>

<style scoped>
    .slidepanel {
        height: 28rem;
        width: 14rem;
        position: fixed;
        left: -10rem;
        top: calc(50% - 14rem);

        -webkit-transition: all 400ms ease-out;
        -moz-transition: all 400ms ease-out;
        -ms-transition: all 400ms ease-out;
        -o-transition: all 400ms ease-out;
        transition: all 400ms ease-out;
    }

    .slidepanel:hover, .slidepanel.show {
        left: 0;

        -webkit-transition: all 400ms ease-in;
        -moz-transition: all 400ms ease-in;
        -ms-transition: all 400ms ease-in;
        -o-transition: all 400ms ease-in;
        transition: all 400ms ease-in;
    }
</style>