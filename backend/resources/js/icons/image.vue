<template>
    <div :class="colorClass">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" />
            <path opacity="0.4"
                d="M9 10C10.1046 10 11 9.10457 11 8C11 6.89543 10.1046 6 9 6C7.89543 6 7 6.89543 7 8C7 9.10457 7.89543 10 9 10Z"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path opacity="0.4"
                d="M2.66992 18.9505L7.59992 15.6405C8.38992 15.1105 9.52992 15.1705 10.2399 15.7805L10.5699 16.0705C11.3499 16.7405 12.6099 16.7405 13.3899 16.0705L17.5499 12.5005C18.3299 11.8305 19.5899 11.8305 20.3699 12.5005L21.9999 13.9005"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>


</template>


<script setup>

const props = defineProps({
    colorClass: {
        type: String,
        default: '#24262D'
    },
});
</script>

