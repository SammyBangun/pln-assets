<script setup>
import { ref, onMounted, defineEmits } from "vue";
import SignaturePad from "signature_pad";
import Notiflix from "notiflix";

const signatureCanvas = ref(null);
let signaturePad = null;
const emit = defineEmits(["signatureSaved"]);

onMounted(() => {
    const canvas = signatureCanvas.value;
    canvas.width = 400;
    canvas.height = 200;
    signaturePad = new SignaturePad(canvas);
});

const clearSignature = () => {
    signaturePad.clear();
};

const saveSignature = () => {
    if (!signaturePad.isEmpty()) {
        const signatureData = signaturePad.toDataURL("image/png");
        emit("signatureSaved", signatureData);
    } else {
        Notiflix.Notify.failure("Tanda tangan belum dibuat!", {
            position: 'center-top',
            distance: '70px',
        });
    }
};
</script>

<template>
    <div>
        <div class="flex justify-center">
            <canvas ref="signatureCanvas" class="signature-canvas mt-5 rounded-lg"></canvas>
        </div>
        <div class="flex justify-around">
            <button type="button" @click="clearSignature"
                class="mt-5 border transform hover:scale-105 transition p-3 rounded-lg bg-red-500 text-white">Reset</button>
            <button type="button" @click="saveSignature"
                class="mt-5 border transform hover:scale-105 transition p-3 rounded-lg bg-green-500 text-white">Simpan</button>
        </div>
    </div>
</template>

<style>
.signature-canvas {
    border: 1px solid #000;
    width: 400px;
    height: 200px;
}
</style>
