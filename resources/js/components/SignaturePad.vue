<script setup>
import { ref, onMounted, defineEmits, onBeforeUnmount } from "vue";
import SignaturePad from "signature_pad";
import Notiflix from "notiflix";

const signatureCanvas = ref(null);
let signaturePad = null;
const emit = defineEmits(["signatureSaved"]);

const resizeCanvas = () => {
    const canvas = signatureCanvas.value;
    if (!canvas) return;

    const parentWidth = canvas.parentElement.offsetWidth;
    canvas.width = parentWidth;
    canvas.height = parentWidth * 0.5;

    signaturePad = new SignaturePad(canvas);
};

onMounted(() => {
    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", resizeCanvas);
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
            position: "center-top",
            distance: "70px",
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
                class="mt-5 border transform hover:scale-105 transition p-3 rounded-lg bg-red-500 text-white">
                Reset
            </button>
            <button type="button" @click="saveSignature"
                class="mt-5 border transform hover:scale-105 transition p-3 rounded-lg bg-green-500 text-white">
                Simpan
            </button>
        </div>
    </div>
</template>

<style>
.signature-canvas {
    border: 2px solid var(--signature-border, #9ca3af);
    width: 100%;
    height: auto;
    max-width: 100%;
}
</style>
