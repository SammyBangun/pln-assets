import { ref } from "vue";

const assets = ref([]);

const fetchAssets = async () => {
    try {
        const response = await fetch("/api/assets");
        if (!response.ok) {
            throw new Error("Gagal mengambil data aset");
        }
        assets.value = await response.json();
    } catch (error) {
        console.error("Terjadi kesalahan:", error);
    }
};

export { assets, fetchAssets };
