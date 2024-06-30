<script setup>
import { ref, watch, onMounted } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import CampaignRecordsTable from "@/Pages/Campaign/CampaignRecordsTable.vue";


const form = useForm({
    name: '',
    description: '',
    file: null,
});

const page = usePage();
const campaign = page.props.campaign || null;
const errorFileName = ref(page.props.errorFileName || null);
const campaignRecords = ref(campaign ? campaign.data.records : []);

watch(() => page.props.errorFileName, (value) => {
    errorFileName.value = value;
});

onMounted(() => {
    if (campaign) {
        form.name = campaign.data.name;
        form.description = campaign.data.description; 
    }
});

const submitForm = () => {
    if(campaign){
        form.post(route("campaign.update", { campaign: campaign.data.id }), {
            preserveScroll: true,
            onSuccess: () => form.reset('file'),
        });
    }else{
        form.post(route("campaign.store"), {
            preserveScroll: true,
            onSuccess: () => form.reset('file'),
        });
    }
    
};
</script>
<template>
    <Head title="Campaigns" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ campaign ? 'Edit Campaign' : 'Create Campaign' }}
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-12">
                    <form @submit.prevent="submitForm">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Campaign Information
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Use this form to {{ campaign ? 'edit' : 'create' }} a campaign.
                                    </p>
                                    <a
                                        :href="`/download-file/sample_campaign_file/sample_campaign_import_file.csv/true`" 
                                        class="text-sm text-indigo-600 underline"
                                    >
                                    Download Sample CSV file for Campaign Import
                                    </a>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="name" class="block text-sm font-medium text-gray-700">
                                            Name
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            v-model="form.name"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{ 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.name }"
                                        />
                                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.name }}
                                        </p>
                                    </div>
                                    
                                    <div class="col-span-6">
                                        <label for="description" class="block text-sm font-medium text-gray-700">
                                            Description
                                        </label>
                                        <textarea
                                            id="description"
                                            v-model="form.description"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{ 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.description }"
                                        ></textarea>
                                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.description }}
                                        </p>
                                    </div>

                                    <div class="col-span-6">
                                        <label for="file" class="block text-sm font-medium text-gray-700">
                                            CSV File
                                        </label>
                                        <input
                                            type="file"
                                            id="file"
                                            accept=".csv"
                                            @change="e => form.file = e.target.files[0]"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            :class="{ 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500': form.errors.file }"
                                        />
                                        <p v-if="form.errors.file" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.file }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="errorFileName" class="mt-6">
                                    <p class="text-sm text-red-500">
                                        There were errors in the uploaded CSV file. Please download the error file for details:
                                        <a :href="`/download-file/error_files/${errorFileName}/true`" class="text-indigo-600 underline">Download Error File</a>
                                    </p>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <Link
                                    :href="route('campaign.index')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                    <CampaignRecordsTable v-if="campaign && campaignRecords.length > 0" :records="campaignRecords" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
