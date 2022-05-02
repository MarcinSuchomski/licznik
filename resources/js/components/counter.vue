<template>
    <div class="container center" id="app">
        <div class="center padding-4">
            <div size="24" id="timer2" v-html="time"></div>
            <div class="buttons">
                <button @click="timerRun" v-if="!timerRunning">Start</button>
                <button @click="timerPause" v-if="timerRunning">Pause</button>
                <button @click="timerReset" v-if="timerRunning">Restart</button>
                <button @click="openForm" v-if="timerRunning">Zapisz Czas</button>
            </div>
            <div v-if="saveTime" class="time-info">
                <div class="container">
                    <div>
                        <h3>Formularz zapisu zadania</h3>
                    </div>
                    <div v-if="errors.length">
                        <b>Please correct the following error(s):</b>
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div v-if="success.length">
                        <b>Please correct the following error(s):</b>
                        <ul>
                            <li v-for="su in success">{{ su }}</li>
                        </ul>
                    </div>
                    <div>
                        <label for="title">Tytul</label>
                        <input type="text" id="title" v-model="timeData.title">
                    </div>
                    <div>
                        <label for="desctiption">Opis</label>
                        <input type="text" id="desctiption" v-model="timeData.description">
                    </div>
                    <div>
                        <button @click="saveWork">Zapisz Czas</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "counter",
    data() {
        return {
            totalTime: 0,
            timerRunning: false,
            timerPaused: false,
            interval: null,
            saveTime: false,
            errors: [],
            success: [],
            timeData: {
                title: '',
                description: '',
                time: '',
            }
        }
    },
    computed: {
        time: function () {
            return this.hours + " : " + this.minutes + " : " + this.seconds;
        },
        hours: function () {
            var h = Math.floor((this.totalTime / 60) / 60);
            return h >= 10 ? h : '0' + h;
        },
        minutes: function () {
            var min = Math.floor(this.totalTime / 60) - (this.hours * 60);
            return min >= 10 ? min : '0' + min;
        },
        seconds: function () {
            var sec = Math.floor(this.hours > 0 ? (this.totalTime % 3600) : (this.totalTime) - (this.minutes * 60));
            return sec >= 10 ? sec : '0' + sec;
        },

    },
    methods: {
        timerRun() {
            this.timerRunning = true;
            this.interval = setInterval(this.counter, 1000);
            this.saveTime = false;
            console.log(this.totalTime);
        },
        timerPause() {
            this.timerRunning = false;
            clearInterval(this.interval);
        },
        timerReset() {
            this.timerRunning = false;
            clearInterval(() => {
                this.interval;
            });
            this.totalTime = 0;
        },
        timerCount() {
            console.log('checking  working ');
            this.timerRunning = true;
            this.interval = setInterval(this.updateCurrentTime, 1000);
            // Counts down from 60 seconds times 1000.
            setInterval(() => {
                this.timerSeconds++
            }, 60 * 1000)

            if (this.timerSeconds === '59') {
                this.timerSeconds = 0;

                setInterval(() => {
                    this.timerSeconds++
                }, 1000);
            } else {
                setInterval(() => {
                    this.timerSeconds++
                }, 1000);
            }

        },
        counter() {
            if (this.timerRunning == true) {
                this.totalTime++;
            }
        },
        openForm() {
            this.timerRunning = false;
            clearInterval(this.interval);
            this.saveTime = true;
            this.timeData.time = this.totalTime;
        },
        checkForm(){
            if (!this.timeData.title) {
                this.errors.push("Name required.");
            }
            if (!this.timeData.description) {
                this.errors.push('Opis required.');
            }

            if (!this.errors.length) {
                return true;
            }

            e.preventDefault();
        },

        saveWork() {
            this.checkForm(),
                this.axios.post('time', this.timeData)
                    .then(({data}) => {
                       // this.$router.push('/login');
                    })
                    .catch((error) => {
                        if (error.response.status == 200) {
                            this.errors.push("informacje zostaly zapisane");
                        } else {
                            this.errors.push(error.response.data.errors);
                        }
                    });
        }
    },
    created() {
        // console.log(this.auth.user);
    }
}
</script>

<style scoped>

</style>
