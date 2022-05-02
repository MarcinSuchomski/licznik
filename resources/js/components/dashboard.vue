<template>
    <div class="container">

        <div id="box">
            <span>From : </span>
            <input type="date" v-model="form.from">

            <span>Until : </span>
            <input type="date" v-model="form.until">
            <input type="button" @click="getData()" value="Filtruj Daty">
        </div>

        <h3 class="p-3 text-center">Lista taskow </h3>

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Title</th>
                <th>Opis</th>
                <th>Czas Pracy (Sec)</th>
                <th>Czas Pracy (Formated)</th>
                <th>Stworzone</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="time in times[0]" :key="time.id">
                <td>{{ time.title }}</td>
                <td>{{ time.description }}</td>
                <td>{{ time.time }}</td>
                <td>{{ time.converted_time }}</td>
                <td>{{ time.created_at }}</td>
            </tr>
            </tbody>
        </table>
        <div class="container" style="background-color:#f1f1f1">
            <button @click="downloadCSVData">Download CSV</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            times: [],
            form: {
                from: this.currentDate(1),
                until: this.currentDate(0),
            },
            isFiltering: false
        };
    },


    methods: {
        getData() {
           // const params = "?from=" + this.form.from + "&to=" + this.form.until
            return axios
                .get("time", {
                    dataType: "json",
                },)
                .then((response) => {
                    this.times.splice(0);
                    //console.log(response.data.data);
                    this.times.push(response.data.data)
                    //console.log(this.times[0]);
                })
                .catch((err) => alert(err))
        },
        currentDate(value) {
            var current = new Date();
            current.setMonth(current.getMonth() - value);
            return current.toISOString().slice(0, 10);
        },
        downloadCSVData() {
            let csv = 'Title,Opis,Czas Pracy (sec),Czas Pracy (Formated),Stworzone\n';
            this.times[0].forEach((row) => {
                csv += row.title + "," + row.description + "," + row.time + "," + row.converted_time + "," + row.created_at + ",";
                csv += "\n";
            });

           const anchor = document.createElement('a');
            anchor.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
            anchor.target = '_blank';
            anchor.download = 'times.csv';
            anchor.click();
        },
    },
    mounted() {
        this.getData();
    },
};
</script>
