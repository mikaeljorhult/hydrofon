<template>
    <li class="resourcelist-resource">
        <label>
            <input type="checkbox"
                   name="resources[]"
                   v-model="checked"
                   v-bind:value="item.id"
                   v-on:change="this.handleClick"
            />
            {{ item.name }}
        </label>
    </li>
</template>

<script>
    import Events from '../modules/events';

    export default {
        props: {
            'item': Object,
        },
        data: function () {
            return {
                checked: this.treeSelected.indexOf(this.item.id) > -1
            };
        },
        inject: ['treeSelected'],
        methods: {
            handleClick: function () {
                Events.$emit('resources-selected', [{
                    id: this.item.id,
                    selected: this.checked
                }]);
            }
        },
        watch: {
            treeSelected: {
                handler: function () {
                    this.checked = this.treeSelected.indexOf(this.item.id) > -1;
                },
                deep: true
            }
        }
    };
</script>