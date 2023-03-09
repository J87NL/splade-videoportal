import "./bootstrap";

import "@protonemedia/laravel-splade/dist/style.css";
import "../css/app.css";
import "../css/buttons.css";
import "../css/custom.css";

import VideoPlayer from "./Components/VideoPlayer.vue";

import { createApp } from "vue/dist/vue.esm-bundler.js";
import { renderSpladeApp, SpladePlugin } from "@protonemedia/laravel-splade";

const el = document.getElementById("app");

createApp({
    render: renderSpladeApp({ el })
})
    .use(SpladePlugin, {
        "max_keep_alive": 10,
        "transform_anchors": false,
        "progress_bar": true,
        "components": {
            VideoPlayer
        },
    })
    .mount(el);
