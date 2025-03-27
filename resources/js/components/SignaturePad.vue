<script setup>
import { ref, onMounted, defineEmits } from "vue";
import SignaturePad from "signature_pad";

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
        alert("Silakan buat tanda tangan terlebih dahulu!");
    }
};
</script>

<style>
.signature-canvas {
    border: 1px solid #000;
    width: 400px;
    height: 200px;
}
</style>

<template>
    <div>
        <canvas ref="signatureCanvas" class="signature-canvas"></canvas>
        <button @click="clearSignature">Clear</button>
        <button @click="saveSignature">Save</button>
    </div>
</template>

<!-- <script>
import SignaturePad from "signature_pad";

export default {
    data() {
        return {
            signaturePad: null
        };
    },
    mounted() {
        const canvas = this.$refs.signatureCanvas;
        canvas.width = 400;
        canvas.height = 200;
        this.signaturePad = new SignaturePad(canvas);
    },
    methods: {
        clearSignature() {
            this.signaturePad.clear();
        },
        saveSignature() {
            if (!this.signaturePad.isEmpty()) {
                const signatureData = this.signaturePad.toDataURL("image/png");
                this.$emit("signatureSaved", signatureData); // Kirim data ke parent
            } else {
                alert("Silakan buat tanda tangan terlebih dahulu!");
            }
        }
    }
};
</script> -->
