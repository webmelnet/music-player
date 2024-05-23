<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
<template>

    <Head title="Song List" />
    <div class="song-list max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <div class="p-4 text-center">
                    <!-- <h2 v-if="currentSong.category.name !== undefined">{{ currentSong.category.name }}</h2> -->
                    <div class="rounded-xl overflow-hidden mb-6 h-[200px]">
                        <img :src="'/' + currentSong.picture" class="w-[100%]" />
                    </div>
                    <h2 class="m-2 text-xl">{{ currentSong.title }}</h2>

                    <div class="inline-block align-middle w-full">
                        <button @click="previousSong"
                            class="bg-gray-500 hover:bg-gray-700  text-white font-bold py-1 px-2 rounded mr-2">
                            <i class="fas fa-backward"></i>
                        </button>
                        <button @click="togglePlay(currentSong)"
                            class="bg-blue-500 text-[30px] px-4 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">
                            <i :class="[isPlaying ? 'fas fa-pause' : 'fas fa-play']"></i>
                        </button>
                        <button @click="nextSong"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded">
                            <i class="fas fa-forward"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center mt-4">
                    <span class="mr-2">{{ formatTime(currentTime) }}</span>
                    <input type="range" v-model="currentTime" :max="duration" @input="seekAudio" class="w-full">
                    <span class="ml-2">{{ formatTime(duration) }}</span>
                </div>
                <audio ref="audioPlayer" :src="'/' + currentSong.url" @loadedmetadata="setDuration"
                    @timeupdate="updateCurrentTime" @ended="nextSong" controls v-show="false"></audio>
            </div>
            <div class="px-4 py-6 col-span-2">
                <div class="py-2 px-4 rounded-xl overflow-hidden" v-for="song in songs" :key="song.id"
                    v-bind:class="(currentSong === song) ? 'bg-gray-200 text-black' : ''">
                    <div class="grid grid-cols-2 gap-4 mb-2 ">
                        <div><span class="text-lg">{{ song.title }}</span><br /><span class="text-sm">{{
                                song.category.name }}</span></div>
                        <div class="text-right">{{ song.duration }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            songs: [],
            currentSong: {},
            isPlaying: false,
            currentTime: 0,
            duration: 0,
        };
    },
    mounted() {
        this.fetchSongs();
    },
    methods: {
        async fetchSongs() {
            try {
                const response = await axios.get('/api/songs');
                this.songs = response.data;
                this.currentSong = this.songs[0];
            } catch (error) {
                console.error(error);
            }
        },
        togglePlay(song) {
            if (this.currentSong === song) {
                if (this.isPlaying) {
                    this.$refs.audioPlayer.pause();
                    this.isPlaying = false;
                } else {
                    this.$refs.audioPlayer.play();
                    this.isPlaying = true;
                }
                return;
            }

            this.currentSong = song;
            this.$refs.audioPlayer.load();

            setTimeout(() => {
                this.$refs.audioPlayer.play();
                this.isPlaying = true;
            }, 1000)
        },
        previousSong() {
            const currentIndex = this.songs.findIndex(song => song.id === this.currentSong.id);
            const previousIndex = (currentIndex - 1 + this.songs.length) % this.songs.length;
            this.currentSong = this.songs[previousIndex];

            setTimeout(() => {
                this.$refs.audioPlayer.load();
                this.$refs.audioPlayer.play();
                this.isPlaying = true;
            }, 1000);
        },
        nextSong() {
            const currentIndex = this.songs.findIndex(song => song.id === this.currentSong.id);
            const nextIndex = (currentIndex + 1) % this.songs.length;
            this.currentSong = this.songs[nextIndex];

            setTimeout(() => {
                this.$refs.audioPlayer.load();
                this.$refs.audioPlayer.play();
                this.isPlaying = true;
            }, 1000);
        },
        playRandom() {
            const randomIndex = Math.floor(Math.random() * this.songs.length);

            if (randomIndex !== this.currentSongIndex) {
                this.currentSongIndex = randomIndex;

                this.togglePlay(this.songs[randomIndex]);
            } else {
                this.playRandom();
            }
        },
        setDuration() {
            this.duration = this.$refs.audioPlayer.duration;
        },
        updateCurrentTime() {
            this.currentTime = this.$refs.audioPlayer.currentTime;
        },
        seekAudio() {
            console.log(this.currentTime);
            this.$refs.audioPlayer.currentTime = this.currentTime;
        },
        formatTime(time) {
            const minutes = Math.floor(time / 60);
            const seconds = Math.floor(time % 60);
            return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        },
    },
};
</script>