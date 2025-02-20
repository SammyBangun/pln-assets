<template>
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="laporan_kerusakan" class="block text-sm font-medium text-gray-700">
                    Laporan Kerusakan
                </label>
                <div class="mt-1">
                    <input 
                        type="text" 
                        id="laporan_kerusakan" 
                        v-model="form.laporan_kerusakan" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        required
                    />
                </div>
                <div v-if="form.errors.laporan_kerusakan" class="text-red-500 text-sm mt-1">
                    {{ form.errors.laporan_kerusakan }}
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>
                <div class="mt-1">
                    <textarea 
                        id="deskripsi" 
                        v-model="form.deskripsi" 
                        rows="4"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        required
                    ></textarea>
                </div>
                <div v-if="form.errors.deskripsi" class="text-red-500 text-sm mt-1">
                    {{ form.errors.deskripsi }}
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <Link 
                    :href="route('riwayat.index')"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                    Cancel
                </Link>
                <button 
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="form.processing"
                >
                    Save Changes
                </button>
            </div>
        </form>

        <div v-if="form.errors.error" class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ form.errors.error }}
        </div>
    </div>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3';

export default {
    components: {
        Link
    },
    
    props: {
        report: {
            type: Object,
            required: true
        }
    },

    setup(props) {
        const form = useForm({
            laporan_kerusakan: props.report.laporan_kerusakan,
            deskripsi: props.report.deskripsi
        });

        function submit() {
            form.put(route('riwayat.update', props.report.id), {
                onSuccess: () => {
                    console.log('Form submitted successfully');
                },
                onError: (errors) => {
                    console.error('Form submission failed:', errors);
                }
            });
        }

        return { form, submit };
    }
}
</script>