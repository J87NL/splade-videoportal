<template>
    <video id="vid1" ref="videoPlayer" class="video-js rounded-md"></video>
</template>

<script>
import videojs from 'video.js';
import 'videojs-youtube/dist/Youtube';
import '@devmobiliza/videojs-vimeo/dist/videojs-vimeo.esm';
import 'video.js/dist/video-js.min.css';
import { default as Axios } from "axios";

export default {
    name: 'VideoPlayer',
    props: {
        src: {
            type: String,
            default() {
                return '';
            }
        },
        type: {
            type: String,
            default() {
                return 'html5';
            }
        },
        slug: {
            type: String,
            default() {
                return '';
            }
        },
    },
    data() {
        return {
            player: null
        }
    },
    mounted() {
        this.player = videojs(this.$refs.videoPlayer, {
            autoplay: true,
            controls: true,
            fluid: true,
            techOrder: [(this.type === 'mp4' ? 'html5' : this.type)],
            sources: [
                {
                    src: this.src,
                    type: 'video/' + this.type,
                }
            ]
        }, () => {
            // this.player.log('onPlayerReady', this);

            setTimeout(() => Axios.post('/viewcount/' + this.slug), 30*1000);
        });
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
        }
    }
}
</script>
