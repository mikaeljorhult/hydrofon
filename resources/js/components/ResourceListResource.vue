<template>
    <li class="resourcelist-resource">
        <label>
            <input type="checkbox"
                   name="resources[]"
                   value="item.id"
            />
            {{ item.name }}
        </label>
    </li>
</template>

<script>
    export default {
        props: {
            'item': Object,
        },
        data: function () {
            return {};
        },
    };
</script>