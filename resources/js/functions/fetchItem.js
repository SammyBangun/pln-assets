async function fetchLatestAssets() {
    try {
        const response = await fetch("http://127.0.0.1:8000/latest-assets");
        if (!response.ok) {
            throw new Error("Gagal mengambil data");
        }
        return await response.json();
    } catch (error) {
        console.error("Error fetching latest assets:", error);
        return [];
    }
}

export default fetchLatestAssets;
