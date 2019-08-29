<template>
    <header>
        <h1>{{ dateString }}</h1>
        <button
            title="Previous"
            v-on:click="handleUpdateDate($store.state.date - 86400)"
        >
            <icon icon="cheveron-left" class="inline-block w-6"></icon>
            <span class="screen-reader">Previous</span>
        </button>
        <button
            title="Next"
            v-on:click="handleUpdateDate($store.state.date + 86400)"
        >
            <span class="screen-reader">Next</span>
            <icon icon="cheveron-right" class="inline-block w-6"></icon>
        </button>
    </header>
</template>

<script>
    import Icon from 'laravel-mix-vue-svgicon/IconComponent';
    import Events from '../modules/events';

    export default {
        props: {
            'date': Number,
        },
        data: function () {
            return {};
        },
        computed: {
            dateString: function () {
                return new Date(this.$store.state.date * 1000).toISOString().split('T')[0];
            }
        },
        methods: {
            handleUpdateDate: function (newDate) {
                this.$store.commit('setDate', newDate);
            }
        },
        components: {
            'icon': Icon,
        }
    };
</script>
