<template>
    <v-app>
        <v-data-table :items="data" :headers="headers" :items-per-page="5">
            <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
            </template>
        </v-data-table>
    </v-app>
</template>

<script>
import axios from "axios";

export default {
    name: "App",

    data: () => ({
        items: ["albums", "todos", "posts"],
        selected: "",

        headers: [
            { text: "USER_ID", align: "start", sortable: false, value: "id" },
            { text: "EMAIL", value: "email" },
            { text: "NAME", value: "name" },
        ],
        data: [],
    }),

    methods: {
        getData() {
            return axios
                .get("https://jsonplaceholder.typicode.com/users/1/", {
                    dataType: "json",
                })
                .then((response) => {
                    this.data.push(response.data)
                })
                .catch((err) => alert(err));
        },
    },

    mounted() {
        this.getData();
    },
};
</script>
