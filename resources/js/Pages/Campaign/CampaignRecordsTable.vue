<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    records: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <div class="mt-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Campaign Records
        </h3>
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Content
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Campaign Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="record in records" :key="record.id" class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ record.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.content }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ record.campaign_date }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
