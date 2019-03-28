<template>
    <ul class="pagination mt-4" v-if="shouldPaginate">
        <li class="page-item" v-show="prevUrl">
            <a class="page-link" href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
                <span aria-hidden="true">&laquo; Previous</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <li class="page-item" :class="{'active': key === (dataSet.current_page - 1)}" v-for="(page, key) in dataSet.last_page">
            <a class="page-link" href="#" @click.prevent="changePage(key)">{{ ++key }}</a>
        </li>

        <li class="page-item" v-show="nextUrl">
            <a class="page-link" href="#" aria-label="Next" rel="next" @click.prevent="page++">
                <span aria-hidden="true">Next &raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['dataSet'],

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            dataSet() {
                //this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            
            page() {
                this.broadcast();
                this.updateUrl();
            }
        },

        created() {
            let query = location.search.match(/page=(\d+)/);
            if (query) {
                this.page = query[1];
            }
        },

        computed: {
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },

        methods: {
            broadcast() {
                this.$emit('update', this.page);
            },
            changePage(page) {
                this.page = page;
            },
            updateUrl() {
                history.pushState(null, null, '?page=' + this.page);
            }
        },
    }
</script>