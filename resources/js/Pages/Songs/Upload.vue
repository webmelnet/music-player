<template>
    <Head title="Upload Song" />
    <div class="song-upload max-w-[1000px] mt-8 mx-auto sm:px-6 lg:px-8 space-y-6 text-white">
        <h2 class="text-2xl font-bold mb-4">Upload Song</h2>
        <form @submit.prevent="uploadSong">
            <div class="mb-4">
                <label for="title" class="block font-bold mb-2">Title:</label>
                <input type="text" id="title" v-model="song.title"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="artist" class="block font-bold mb-2">Artist:</label>
                <input type="text" id="artist" v-model="song.artist"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="duration" class="block font-bold mb-2">Duration:</label>
                <input type="text" id="duration" v-model="song.duration"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="category" class="block font-bold mb-2">Category:</label>
                <select id="category" v-model="song.category_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                    </option>
                </select>
            </div>
            <div class="mb-4">
                <label for="picture" class="block font-bold mb-2">Picture:</label>
                <input type="file" id="picture" ref="pictureFile"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="audio" class="block font-bold mb-2">Audio File:</label>
                <input type="file" id="audio" ref="audioFile"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            song: {
                title: '',
                artist: '',
                duration: '',
                category_id: '',
            },
            categories: [],
        };
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await axios.get('/api/categories');
                this.categories = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async uploadSong() {
            try {
                const formData = new FormData();
                formData.append('title', this.song.title);
                formData.append('artist', this.song.artist);
                formData.append('duration', this.song.duration);
                formData.append('category_id', this.song.category_id);
                formData.append('audio', this.$refs.audioFile.files[0]);

                if (this.$refs.pictureFile.files[0]) {
                    formData.append('picture', this.$refs.pictureFile.files[0]);
                }

                const response = await axios.post('/api/songs', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                console.log(response.data);
                // Reset form fields and show success message
                this.resetForm();
                alert('Song uploaded successfully!');
            } catch (error) {
                console.error(error);
                alert('An error occurred while uploading the song.');
            }
        },
        resetForm() {
            this.song = {
                title: '',
                artist: '',
                duration: '',
                category_id: '',
            };
            this.$refs.pictureFile.value = '';
            this.$refs.audioFile.value = '';
        },
    },
};
</script>