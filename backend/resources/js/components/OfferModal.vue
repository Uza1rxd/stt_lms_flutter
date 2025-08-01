<!-- src/components/OfferModal.vue -->
<template>
    <div v-if="showModal && masterStore?.masterData?.show_banner" class="popup-wrapper">
        <div class="offer">
            <button class="close-btn" @click="closeModal">
                <i class="bi bi-x"></i>
            </button>
            <img @click="handleClick" :src="banner.thumbnail" class="rounded" alt="banner offer image">
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useMasterStore } from '@/stores/master'
import { useRouter } from 'vue-router';

const masterStore = useMasterStore();
const router = useRouter();
const showModal = ref(false)
const banner = ref({})

onMounted(async () => {
    if (masterStore?.masterData?.show_banner == 1) {
        const response = await axios.get('/banner/list')
        banner.value = response.data.data;
        showModal.value = true
    }
})

function closeModal() {
    showModal.value = false
}


const handleClick = () => {
    router.push('/courses')
    showModal.value = false
}

</script>

<style scoped>
.popup-wrapper {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.offer {
    background: white;
    padding: 0.4rem;
    border-radius: 1rem;
    max-width: 90%;
    width: 800px;
    height: 600px;
    text-align: center;
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.offer img {
    max-width: 100%;
    height: 100%;
    object-fit: fill;
}

.offer h2 {
    margin-bottom: 0.5rem;
    font-size: 1.8rem;
}

.modofferal p {
    color: #555;
    margin-bottom: 1.5rem;
}

.offer button {
    background-color: #ddd;
    color: #000000;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 2rem;
    margin: 0;
    transition: all 0.4s ease-in-out;
}

.offer button:hover {
    background-color: #000000;
    color: #fff;
}

.close-btn {
    position: absolute;
    top: -40px;
    right: -30px;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #aaa;
    cursor: pointer;
}

.close-btn:hover {
    color: #555;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width:992px) {
    .offer {
        width: 70%;
        height: 60%;
    }
}

@media (max-width:767px) {
    .offer {
        width: 70%;
        height: 50%;
    }
}

@media (max-width:576px) {
    .offer {
        width: 70%;
        height: 40%;
    }

    .offer button {
        width: 30px;
        height: 30px;
        font-size: 1.5rem;
    }

    .close-btn {
        top: -30px;
        right: -20px;
    }
}
</style>
