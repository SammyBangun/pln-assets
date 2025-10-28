<script setup>
import { ref, watch } from 'vue';
import SignaturePad from '@/components/SignaturePad.vue';

const props = defineProps({
    show: Boolean,
});

const emits = defineEmits(['close', 'confirmed']);

const signatureData = ref(null);

const handleSignatureConfirmed = (data) => {
    signatureData.value = data;
};

const closeModal = () => {
    emits('close');
};

const confirmSignature = () => {
    if (signatureData.value) {
        emits('confirmed', signatureData.value);
        closeModal();
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96 relative">
            <h2 class="text-lg font-bold mb-4 text-center text-gray-800">Verifikasi Tanda Tangan</h2>

            <SignaturePad @signatureSaved="handleSignatureConfirmed" />

            <div v-if="signatureData" class="mt-4">
                <p class="text-gray-700">Preview Tanda Tangan:</p>
                <div class="flex justify-center">
                    <img :src="signatureData" alt="Preview Signature" class="max-w-full h-auto rounded-lg shadow-md">
                </div>
            </div>

            <div class="flex justify-between mt-4">
                <button @click="closeModal" class="px-4 py-2 rounded bg-gray-400 hover:bg-gray-500 text-white">
                    Batal
                </button>
                <button @click="confirmSignature"
                    class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white disabled:opacity-50 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    :disabled="!signatureData">
                    Kirim
                </button>
            </div>
        </div>
    </div>
</template>
