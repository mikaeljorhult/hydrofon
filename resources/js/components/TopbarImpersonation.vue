<template>
    <section>
        <div class="flex items-center">
            <icon icon="view-show" class="w-4 flex-shrink-0"></icon>

            <label for="user_id" class="screen-reader">
                Select user to impersonate
            </label>

            <v-select
                name="user_id"
                placeholder="Impersonate user..."
                label="name"
                v-model="selectedUser"
                v-bind:class="{ 'flex-grow': true, 'vs__empty': users.length === 0 }"
                v-bind:searchable="true"
                v-bind:clearable="false"
                v-bind:options="users"
                v-bind:reduce="user => user.id"
                v-on:input="onSelect"
                v-on:search="onSearchChange"
            >
                <span slot="open-indicator"></span>
                <span slot="no-options">Type to start search...</span>
            </v-select>
        </div>
    </section>
</template>

<script>
    import axios from 'axios';
    import debounce from 'lodash/debounce';
    import vSelect from 'vue-select';
    import Icon from 'laravel-mix-vue-svgicon/IconComponent';

    export default {
        props: {},
        data: function () {
            return {
                users: [],
                selectedUser: []
            };
        },
        methods: {
            getUsers: debounce(function (query, loading) {
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
                        loading(false);
                    });
            }, 200),
            onSearchChange: function (query, loading) {
                if (query.length < 3) {
                    this.users = [];
                    return;
                }

                loading(true);
                this.getUsers(query, loading);
            },
            onSelect: function (selected) {
                axios.post("impersonation", {
                    "user_id": selected,
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
            'v-select': vSelect,
        }
    };
</script>