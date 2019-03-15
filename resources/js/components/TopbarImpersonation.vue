<template>
    <section>
        <div class="flex items-center">
            <icon icon="view-show" class="w-4 flex-no-shrink"></icon>

            <label for="user_id" class="screen-reader">
                Select user to impersonate
            </label>

            <Multiselect
                    name="user_id"
                    placeholder="Impersonate user..."
                    label="name"
                    trackBy="id"
                    v-model="selectedUser"
                    v-bind:options="users"
                    v-bind:searchable="true"
                    v-bind:showNoOptions="false"
                    v-bind:showNoResults="false"
                    v-bind:loading="isLoading"
                    v-on:search-change="onSearchChange"
                    v-on:select="onSelect"
            ></Multiselect>
        </div>
    </section>
</template>

<script>
    import axios from 'axios';
    import debounce from 'lodash/debounce';
    import Multiselect from 'vue-multiselect';
    import Icon from 'laravel-mix-vue-svgicon/IconComponent';

    export default {
        props: {},
        data: function () {
            return {
                isLoading: false,
                users: [],
                selectedUser: []
            };
        },
        methods: {
            getUsers: debounce(function (query) {
                axios.get("api/users", {
                    params: {
                        "filter[name]": query,
                        "filter[is_admin]": 0
                    }
                })
                    .then(response => {
                        this.users = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            }, 200),
            onSearchChange: function (query) {
                if (query.length < 3) {
                    this.users = [];
                    return;
                }

                this.isLoading = true;
                this.getUsers(query);
            },
            onSelect: function (selected) {
                axios.post("impersonation", {
                    "user_id": selected.id,
                })
                    .then(response => {
                        window.location = '/calendar';
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        components: {
            'icon': Icon,
            'Multiselect': Multiselect,
        }
    };
</script>