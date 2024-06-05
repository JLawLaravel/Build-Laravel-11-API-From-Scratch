import "./bootstrap";

import { createApp } from "vue";
import PostsIndex from "./components/Posts/Index.vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import { TailwindPagination } from "laravel-vue-pagination";

createApp({}).component("PostsIndex", PostsIndex).mount("#app");
